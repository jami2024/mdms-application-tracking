<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateTemplateController extends Controller
{
    public function index()
    {
        $templates = CertificateTemplate::withCount('certificates')->orderBy('name')->paginate(15);
        return view('admin.certificate-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.certificate-templates.create');
    }

    public function store(Request $request)
    {
        try {
                DB::beginTransaction();
                $data = $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'module' => ['required', 'in:company,establishment,device,package,final_package_approval,mrp'],
                    'html_content' => ['required', 'string'],
                ]);

                CertificateTemplate::create([...$data, 'is_active' => true]);
                DB::commit();
                return redirect()->route('admin.certificate-templates.index')->with('status', 'Template created.');
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            return back()->with('error', 'An error occurred while creating the template: ' . $th->getMessage());
        }
    }

    public function edit(CertificateTemplate $certificateTemplate)
    {
        return view('admin.certificate-templates.edit', ['template' => $certificateTemplate]);
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'module' => ['required', 'in:company,establishment,device,package,final_package_approval,mrp'],
            'html_content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $certificateTemplate->update([...$data, 'is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.certificate-templates.index')->with('status', 'Template updated.');
    }

    public function destroy(CertificateTemplate $certificateTemplate)
    {
        if ($certificateTemplate->certificates()->exists()) {
            return back()->with('error', 'Template is already used by issued certificates and cannot be deleted.');
        }

        $certificateTemplate->delete();
        return back()->with('status', 'Template deleted.');
    }
}
