<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->withCount('employees')->orderBy('title')->get();
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0|gte:min_salary',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Position created successfully!');
    }

    public function show(string $id)
    {
        $position = Position::with('department', 'employees')->findOrFail($id);
        return view('positions.show', compact('position'));
    }

    public function edit(string $id)
    {
        $position = Position::findOrFail($id);
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
    }

    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);
        
        if ($position->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete position with employees!');
        }
        
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}
