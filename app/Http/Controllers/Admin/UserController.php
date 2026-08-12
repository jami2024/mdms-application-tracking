<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Designation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->with(['designation', 'organization', 'roles'])
            ->when($request->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->role, fn ($q) => $q->whereHas('roles', fn ($q) => $q->where('name', $request->role)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::pluck('name');
        $designations = Designation::where('status', 'active')->get();
        $organizations = Organization::where('status', 'active')->get();

        return view('admin.users.create', compact('roles', 'designations', 'organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'user_type' => ['required', 'in:admin,staff,applicant'],
            'status' => ['required', 'in:active,inactive,pending'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            ...collect($data)->except(['password', 'role'])->toArray(),
            'password' => Hash::make($data['password']),
            'created_by' => auth()->id(),
        ]);

        $user->assignRole($data['role']);

        activity('user')->causedBy(auth()->user())->performedOn($user)
            ->log('User account created');

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');
        $designations = Designation::where('status', 'active')->get();
        $organizations = Organization::where('status', 'active')->get();

        return view('admin.users.edit', compact('user', 'roles', 'designations', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'user_type' => ['required', 'in:admin,staff,applicant'],
            'status' => ['required', 'in:active,inactive,suspended,pending'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->update(collect($data)->except('role')->toArray());
        $user->syncRoles([$data['role']]);

        activity('user')->causedBy(auth()->user())->performedOn($user)
            ->log('User account updated');

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        activity('user')->causedBy(auth()->user())->performedOn($user)->log('User account deleted');

        return back()->with('status', 'User deleted.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:8']]);

        $user->update(['password' => Hash::make($data['password'])]);

        activity('user')->causedBy(auth()->user())->performedOn($user)->log('Password reset by admin');

        return back()->with('status', "Password reset for {$user->name}.");
    }

    public function toggleStatus(Request $request, User $user)
    {
        $data = $request->validate(['status' => ['required', 'in:active,inactive,suspended,pending']]);

        $user->update(['status' => $data['status']]);

        activity('user')->causedBy(auth()->user())->performedOn($user)
            ->withProperties(['status' => $data['status']])
            ->log('User status changed');

        return back()->with('status', "Status updated to {$data['status']}.");
    }

    public function profile()
    {
        return view('admin.users.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required', 'mimes:xlsx,csv']]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('status', 'Users imported successfully.');
    }
}
