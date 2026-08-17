<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PaymentGatewayInterface $gateway)
    {
    }

    public function index(Request $request)
    {
        $payments = Payment::with('application.workflowConfig')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    // Shown on an approved application that hasn't been paid for yet.
    public function create(Application $application)
    {
        abort_unless($application->applicant_id === auth()->id(), 403);

        if ($application->status !== 'approved') {
            return back()->with('error', 'The fee is payable once the application is approved.');
        }

        if ($application->payments()->whereIn('status', ['paid', 'reconciled'])->exists()) {
            return redirect()->route('applications.show', $application)->with('status', 'This application is already paid for.');
        }

        $amount = config('fees.' . $application->workflowConfig->module, 0);

        return view('payments.create', compact('application', 'amount'));
    }

    public function store(Request $request, Application $application)
    {
        abort_unless($application->applicant_id === auth()->id(), 403);

        $data = $request->validate([
            'method' => ['required', 'in:SSLCOMMERZ,bKash,Nagad,Rocket,TR Challan'],
        ]);

        $amount = config('fees.' . $application->workflowConfig->module, 0);

        $payment = Payment::create([
            'reference' => 'TXN' . strtoupper(Str::random(10)),
            'application_id' => $application->id,
            'user_id' => auth()->id(),
            'description' => ucfirst($application->workflowConfig->module) . ' application fee — ' . $application->application_no,
            'method' => $data['method'],
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        return redirect()->away($this->gateway->initiate($payment));
    }

    // Mock-gateway-only: a stand-in checkout page with Success/Fail buttons,
    // so the flow is fully exercisable without a real gateway account.
    public function sandbox(Payment $payment)
    {
        abort_if(config('services.payment.driver', 'mock') !== 'mock', 404);

        return view('payments.sandbox', compact('payment'));
    }

    public function callbackSuccess(Request $request, Payment $payment)
    {
        if ($this->gateway->verify($payment, [...$request->all(), 'reference' => $payment->reference])) {
            $payment->update([
                'status' => 'paid',
                'gateway_transaction_id' => $request->input('val_id', $payment->reference),
                'paid_at' => now(),
            ]);

            activity('payment')->causedBy(auth()->user())->performedOn($payment)->log('Payment completed');

            return redirect()->route('payments.show', $payment)->with('status', 'Payment successful.');
        }

        $payment->update(['status' => 'failed']);
        return redirect()->route('payments.show', $payment)->with('error', 'Payment verification failed.');
    }

    public function callbackFail(Payment $payment)
    {
        $payment->update(['status' => 'failed']);
        return redirect()->route('payments.show', $payment)->with('error', 'Payment failed.');
    }

    public function callbackCancel(Payment $payment)
    {
        $payment->update(['status' => 'failed']);
        return redirect()->route('applications.show', $payment->application)->with('error', 'Payment cancelled.');
    }

    public function show(Payment $payment)
    {
        abort_unless($payment->user_id === auth()->id() || auth()->user()->hasRole('Admin'), 403);
        $payment->load('application.workflowConfig');

        return view('payments.show', compact('payment'));
    }

    // Staff-side: mark a manually reconciled payment (e.g. bank/TR challan
    // confirmed offline) so the certificate-issuance gate unblocks.
    public function reconcile(Payment $payment)
    {
        $payment->update(['status' => 'reconciled', 'reconciled_at' => now()]);

        activity('payment')->causedBy(auth()->user())->performedOn($payment)->log('Payment reconciled by staff');

        return back()->with('status', 'Payment marked as reconciled.');
    }
}
