<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function index()
    {
        $upazilas = Upazila::with('district.division')->orderBy('name')->paginate(15);
        return view('admin.settings.upazilas.index', compact('upazilas'));
    }

    public function create()
    {
        $districts = District::with('division')->where('status', 'active')->get();
        return view('admin.settings.upazilas.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:upazilas,code'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Upazila::create($data);
        return redirect()->route('admin.upazilas.index')->with('status', 'Upazila created.');
    }

    public function edit(Upazila $upazila)
    {
        $districts = District::with('division')->where('status', 'active')->get();
        return view('admin.settings.upazilas.edit', compact('upazila', 'districts'));
    }

    public function update(Request $request, Upazila $upazila)
    {
        $data = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:upazilas,code,' . $upazila->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $upazila->update($data);
        return redirect()->route('admin.upazilas.index')->with('status', 'Upazila updated.');
    }

    public function destroy(Upazila $upazila)
    {
        $upazila->delete();
        return back()->with('status', 'Upazila deleted.');
    }
}
