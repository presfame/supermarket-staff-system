<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('employee')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('users.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,hr,supervisor,employee',
            'employee_id' => 'nullable|exists:employees,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $employees = Employee::all();
        return view('users.edit', compact('user', 'employees'));
    }

    public function show($id)
    {
        $user = User::with('employee')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,hr,supervisor,employee',
            'employee_id' => 'nullable|exists:employees,id',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted');
    }
}
