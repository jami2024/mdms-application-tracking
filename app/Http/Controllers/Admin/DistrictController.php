<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('division')->withCount('upazilas')->orderBy('name')->paginate(15);
        return view('admin.settings.districts.index', compact('districts'));
    }

    public function create()
    {
        $divisions = Division::where('status', 'active')->get();
        return view('admin.settings.districts.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:districts,code'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        District::create($data);
        return redirect()->route('admin.districts.index')->with('status', 'District created.');
    }

    public function edit(District $district)
    {
        $divisions = Division::where('status', 'active')->get();
        return view('admin.settings.districts.edit', compact('district', 'divisions'));
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:districts,code,' . $district->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $district->update($data);
        return redirect()->route('admin.districts.index')->with('status', 'District updated.');
    }

    public function destroy(District $district)
    {
        if ($district->upazilas()->exists()) {
            return back()->with('error', 'Remove its upazilas first.');
        }
        $district->delete();
        return back()->with('status', 'District deleted.');
    }
}
