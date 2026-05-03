<?php

namespace App\Http\Controllers;

use App\Models\MechanicAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'User');
    }

    public function index(Request $request)
    {
        $query = User::query()->latest();
        $role = $request->query('role');

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->get();
        $attendanceSummary = collect();

        if ((backend_user()?->role ?? null) === 'owner') {
            $attendanceSummary = MechanicAttendance::selectRaw('user_id, MAX(attendance_date) as last_attendance_date')
                ->with('user')
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');
        }

        return view('users.index', compact('users', 'role', 'attendanceSummary'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'role' => 'required|in:admin,mekanik,kasir,customer,owner',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->to(backend_route('admin.users.index'))->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:admin,mekanik,kasir,customer,owner',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->to(backend_route('admin.users.index'))->with('success', 'User berhasil diperbarui.');
    }
}
