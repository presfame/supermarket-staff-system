<?php

namespace App\Http\Controllers;

use App\Models\{Employee, Department, Position, User};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with(['department', 'position'])->get();
        $departments = Department::where('is_active', true)->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function show($id)
    {
        $employee = Employee::with(['department', 'position', 'attendances', 'payrolls'])->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'id_number' => 'required|unique:employees',
            'phone' => 'required',
            'email' => 'nullable|email|unique:employees,email|unique:users,email',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'date_of_hire' => 'required|date',
            'pay_type' => 'required|in:Hourly,Monthly',
            'basic_salary' => 'required|numeric|min:0',
            'create_user' => 'nullable|in:on,1',
            'password' => 'nullable|required_if:create_user,on|min:6',
        ]);

        $validated['employee_number'] = $this->generateEmployeeNumber();

        DB::transaction(function () use ($validated, $request) {
            $employee = Employee::create($validated);

            // Optionally create a User account for the employee
            if ($request->has('create_user') && $request->input('create_user')) {
                $pw = $validated['password'] ?? 'password';
                User::create([
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $validated['email'] ?? $employee->email,
                    'password' => Hash::make($pw),
                    'role' => 'employee',
                    'employee_id' => $employee->id,
                    'is_active' => true,
                ]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Employee added!');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'basic_salary' => 'required|numeric',
            'employment_status' => 'required|in:Active,Inactive,Suspended,Terminated',
        ]);

        $employee->update($validated);

        // If this employee has a linked user account, sync their active state
        $user = User::where('employee_id', $employee->id)->first();
        if ($user) {
            $user->is_active = $employee->employment_status === 'Active';
            $user->save();
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated!');
    }

    private function generateEmployeeNumber()
    {
        $year = date('Y');
        $last = Employee::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $num = $last ? (int)substr($last->employee_number, -4) + 1 : 1;
        return 'EMP' . $year . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
