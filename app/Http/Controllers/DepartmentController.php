<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:20|unique:departments',
            'description' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    public function show(string $id)
    {
        $department = Department::with('employees', 'positions')->findOrFail($id);
        return view('departments.show', compact('department'));
    }

    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:20|unique:departments,code,' . $id,
            'description' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department with employees!');
        }
        
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
