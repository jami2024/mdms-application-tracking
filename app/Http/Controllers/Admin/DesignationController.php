<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Organization;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::with('organization')->orderBy('grade_level')->paginate(15);
        return view('admin.settings.designations.index', compact('designations'));
    }

    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('admin.settings.designations.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_code' => ['required', 'string', 'max:20', 'unique:designations,short_code'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'grade_level' => ['nullable', 'integer'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Designation::create($data);

        return redirect()->route('admin.designations.index')->with('status', 'Designation created.');
    }

    public function edit(Designation $designation)
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('admin.settings.designations.edit', compact('designation', 'organizations'));
    }

    public function update(Request $request, Designation $designation)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_code' => ['required', 'string', 'max:20', 'unique:designations,short_code,' . $designation->id],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'grade_level' => ['nullable', 'integer'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $designation->update($data);

        return redirect()->route('admin.designations.index')->with('status', 'Designation updated.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return back()->with('status', 'Designation deleted.');
    }
}
