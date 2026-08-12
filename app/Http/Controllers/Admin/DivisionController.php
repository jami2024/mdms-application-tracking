<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('districts')->orderBy('name')->paginate(15);
        return view('admin.settings.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('admin.settings.divisions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:divisions,code'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Division::create($data);
        return redirect()->route('admin.divisions.index')->with('status', 'Division created.');
    }

    public function edit(Division $division)
    {
        return view('admin.settings.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:divisions,code,' . $division->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $division->update($data);
        return redirect()->route('admin.divisions.index')->with('status', 'Division updated.');
    }

    public function destroy(Division $division)
    {
        if ($division->districts()->exists()) {
            return back()->with('error', 'Remove its districts first.');
        }
        $division->delete();
        return back()->with('status', 'Division deleted.');
    }
}
