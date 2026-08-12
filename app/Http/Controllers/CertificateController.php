<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Support\PdfGenerator;

class CertificateController extends Controller
{
    public function create(Application $application)
    {
        if ($application->status !== 'approved') {
            return back()->with('error', 'Only approved applications can have a certificate issued.');
        }

        if (! $application->payments()->whereIn('status', ['paid', 'reconciled'])->exists()) {
            return back()->with('error', 'The application fee must be paid before a certificate can be issued.');
        }

        if ($application->certificate) {
            return redirect()->route('certificates.show', $application->certificate);
        }

        $templates = CertificateTemplate::where('module', $application->workflowConfig->module)
            ->where('is_active', true)
            ->get();

        return view('certificates.create', compact('application', 'templates'));
    }

    public function store(Request $request, Application $application)
    {
        $data = $request->validate([
            'certificate_template_id' => ['required', 'exists:certificate_templates,id'],
            'signature_type' => ['required', 'in:digital,uploaded'],
            'signature_file' => ['required_if:signature_type,uploaded', 'nullable', 'image', 'max:2048'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after:issue_date'],
        ]);

        $certificateNo = $this->generateCertificateNo($application);

        $signaturePath = null;
        if ($request->hasFile('signature_file')) {
            $signaturePath = $request->file('signature_file')->store('signatures', 'public');
        }

        $certificate = Certificate::create([
            'application_id' => $application->id,
            'certificate_template_id' => $data['certificate_template_id'],
            'certificate_no' => $certificateNo,
            'signature_type' => $data['signature_type'],
            'signature_file_path' => $signaturePath,
            'signed_by' => auth()->id(),
            'issue_date' => $data['issue_date'],
            'expiry_date' => $data['expiry_date'] ?? null,
            'status' => 'active',
        ]);

        // QR encodes the public verification URL — this is what a scanner reads off the PDF.
        $verifyUrl = route('certificates.verify', $certificate->certificate_no);
        $qrPath = 'qr/' . $certificate->certificate_no . '.png';

        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: $certificate->certificate_no
        );

        $result = $writer->write($qrCode);

        $result->saveToFile(storage_path('app/public/qr/'.$certificate->certificate_no.'.png'));


        $certificate->update(['qr_code_path' => $qrPath]);

        $this->generatePdf($certificate);

        activity('certificate')->causedBy(auth()->user())->performedOn($certificate)
            ->log('Certificate issued');

        return redirect()->route('certificates.show', $certificate)->with('status', 'Certificate issued.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load('application.applicant', 'template', 'signedBy');
        return view('certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        if (! $certificate->pdf_path || ! Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->generatePdf($certificate);
            $certificate->refresh();
        }

        return Storage::disk('public')->download($certificate->pdf_path, $certificate->certificate_no . '.pdf');
    }

    // Same file as download(), but served inline (no attachment header) so it
    // can render inside the <iframe> preview on the certificate detail page.
    public function preview(Certificate $certificate)
    {
        if (! $certificate->pdf_path || ! Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->generatePdf($certificate);
            $certificate->refresh();
        }

        return response(Storage::disk('public')->get($certificate->pdf_path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $certificate->certificate_no . '.pdf"',
        ]);
    }

    public function revoke(Certificate $certificate)
    {
        $certificate->update(['status' => 'revoked']);

        activity('certificate')->causedBy(auth()->user())->performedOn($certificate)->log('Certificate revoked');

        return back()->with('status', 'Certificate revoked.');
    }

    // Public — no auth. This is where a QR scan lands.
    public function verify(string $certificateNo)
    {
        $certificate = Certificate::with('application.applicant', 'template')
            ->where('certificate_no', $certificateNo)
            ->first();

        return view('certificates.verify', compact('certificate', 'certificateNo'));
    }

    private function generateCertificateNo(Application $application): string
    {
        $prefix = strtoupper(substr($application->workflowConfig->module, 0, 3));
        $year = now()->year;
        $sequence = Certificate::whereYear('created_at', $year)->count() + 1;

        return sprintf('MDMS-%s-%d-%05d', $prefix, $year, $sequence);
    }

    private function generatePdf(Certificate $certificate): void
    {
        $certificate->loadMissing('application.applicant', 'template', 'signedBy');

        $html = $this->renderTemplate($certificate);

        $pdfPath = 'certificates/' . $certificate->certificate_no . '.pdf';
        Storage::disk('public')->put($pdfPath, PdfGenerator::htmlToBytes($html));

        $certificate->update(['pdf_path' => $pdfPath]);
    }

    // Simple {{placeholder}} substitution rather than compiling the stored HTML
    // as Blade — templates are admin-authored content, not trusted PHP code.
    private function renderTemplate(Certificate $certificate): string
    {
        $applicable = $certificate->application->applicable;
        $govEmblemPath = public_path('images/gov-emblem.svg');

        // Eloquent returns null (not an error) for attributes a model doesn't
        // have, so these are safe to read even when $applicable is an
        // Establishment/Device/MrpApplication rather than a Company.
        $address = trim(collect([$applicable->address_line_1 ?? null, $applicable->address_line_2 ?? null])->filter()->implode(', '))
            ?: ($applicable->address ?? '');

        $validity = 'আজীবন';
        if ($certificate->issue_date && $certificate->expiry_date) {
            $months = $certificate->issue_date->diffInMonths($certificate->expiry_date);
            $validity = $months >= 12 && $months % 12 === 0
                ? intdiv($months, 12) . ' বছর'
                : $months . ' মাস';
        }

        $tokens = [
            '{{certificate_no}}' => $certificate->certificate_no,
            '{{issue_date}}' => optional($certificate->issue_date)->format('d M, Y'),
            '{{expiry_date}}' => optional($certificate->expiry_date)->format('d M, Y') ?: 'আজীবন',
            '{{validity_period}}' => $validity,
            '{{applicant_name}}' => $certificate->application->applicant->name ?? '',
            '{{entity_name}}' => $applicable->name ?? $applicable->device_name ?? '',
            '{{organization_type_label}}' => $applicable->organization_type ? \App\Support\Bengali::label($applicable->organization_type) : '',
            '{{address}}' => $address,
            '{{tin_no}}' => $applicable->tin_no ?? '',
            '{{bin_no}}' => $applicable->bin_no ?? '',
            '{{trade_license_no}}' => $applicable->trade_license_no ?? '',
            '{{module_label}}' => \App\Support\Bengali::label($certificate->template->module),
            '{{org_name}}' => 'ঔষধ প্রশাসন অধিদপ্তর',
            '{{gov_name}}' => 'গণপ্রজাতন্ত্রী বাংলাদেশ সরকার',
            '{{gov_emblem}}' => file_exists($govEmblemPath) ? '<img src="' . $govEmblemPath . '" width="70">' : '',
            '{{qr_code}}' => $certificate->qr_code_path
                ? '<img src="' . Storage::disk('public')->path($certificate->qr_code_path) . '" width="110">'
                : '',
            '{{signature}}' => $certificate->signature_file_path
                ? '<img src="' . Storage::disk('public')->path($certificate->signature_file_path) . '" height="55">'
                : '<span style="font-family: kalpurush;">ডিজিটালি স্বাক্ষরিত</span>',
            '{{signed_by}}' => $certificate->signedBy->name ?? '',
        ];

        return strtr($certificate->template->html_content, $tokens);
    }

    public function generatePackagingDemoCertificate()
    {
        // $certificate->loadMissing('application.applicant', 'template', 'signedBy');

        $pdf_name = 'demo_' . Str::random(8);

        // 1. Generate QR Code using Endroid\QrCode
        $qrUrl = route('certificates.verify', $pdf_name); // Unique dummy certificate number for demo
        $qrCode = new QrCode($qrUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Convert PNG binary output to Base64 for blade rendering
        $qrCodeBase64 = base64_encode($result->getString());

        // 2. Render the packaging demo HTML template
        $html = view('pdf.packaging_demo_certificate', [
            'certificate' => [
                'certificate_no' => $pdf_name,
                'issue_date' => now()->format('d M, Y'),
                'expiry_date' => now()->addYear()->format('d M, Y'),
                'validity_period' => '1 বছর',
                'applicant_name' => 'ডেমো প্রতিষ্ঠান',
                'entity_name' => 'ডেমো কোম্পানি লিমিটেড',
                'organization_type_label' => 'প্রাইভেট লিমিটেড কোম্পানি',
                'address' => '১২৩, ডেমো রোড, ঢাকা, বাংলাদেশ',
                'tin_no' => '1234567890',
                'bin_no' => '0987654321',
                'trade_license_no' => 'DL-123456',
                'template_name' => 'Standard Demo Template',
                'signed_by' => 'Authorized Signatory',
            ],
            'qrCode' => $qrCodeBase64,
        ])->render();

        // 3. Convert HTML to bytes and store on the public disk
        $pdfPath = 'certificates/demo_' . $pdf_name . '.pdf';
        $pdfBytes = PdfGenerator::htmlToBytes($html);
        Storage::disk('public')->put($pdfPath, $pdfBytes);

        // 4. Update certificate path
        // $certificate->update(['pdf_path' => $pdfPath]);

        // 5. Direct inline display in the browser
        $fullPath = Storage::disk('public')->path($pdfPath);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="demo_' . $pdf_name . '.pdf"',
        ]);
    }
}
