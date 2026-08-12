<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Organization;
use App\Models\OrganogramPosition;
use App\Models\User;
use Illuminate\Http\Request;

class OrganogramController extends Controller
{
    public function index()
    {
        // Root positions with recursive children eager-loaded for the tree view.
        $roots = OrganogramPosition::with('designation', 'incumbent', 'organization')
            ->whereNull('parent_id')
            ->with('children.designation', 'children.incumbent', 'children.children.designation', 'children.children.incumbent')
            ->get();

        return view('admin.organogram.index', compact('roots'));
    }

    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $positions = OrganogramPosition::with('designation')->get();
        $users = User::where('status', 'active')->get();

        return view('admin.organogram.create', compact('organizations', 'designations', 'positions', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'designation_id' => ['required', 'exists:designations,id'],
            'parent_id' => ['nullable', 'exists:organogram_positions,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'order' => ['nullable', 'integer'],
        ]);

        $position = OrganogramPosition::create([...$data, 'status' => 'active']);

        activity('organogram')->causedBy(auth()->user())->performedOn($position)->log('Organogram position created');

        return redirect()->route('admin.organogram.index')->with('status', 'Position added to the organogram.');
    }

    public function destroy(OrganogramPosition $organogram)
    {
        if ($organogram->children()->exists()) {
            return back()->with('error', 'Reassign or remove child positions first.');
        }

        $organogram->delete();

        return back()->with('status', 'Position removed.');
    }
}
