<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()->withCount(['programs', 'courses', 'instructors'])->orderBy('dept_name')->get();

        return view('departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDepartment($request);
        DB::transaction(function () use ($validated): void {
            $nextId = ((int) Department::query()->lockForUpdate()->pluck('dept_id')->max()) + 1;
            Department::query()->create($validated + ['dept_id' => $nextId, 'is_active' => true]);
        });

        return redirect()->route('departments.index')->with('success', 'Department successfully created.');
    }

    public function show(Department $department): View
    {
        $department->load(['programs.courses', 'instructors']);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $department->update($this->validateDepartment($request, $department));

        return redirect()->route('departments.show', $department)->with('success', 'Department successfully updated.');
    }

    public function toggle(Department $department): RedirectResponse
    {
        $department->update(['is_active' => ! $department->is_active]);

        return back()->with('success', $department->is_active ? 'Department activated.' : 'Department deactivated.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->programs()->exists() || $department->instructors()->exists()) {
            return back()->withErrors(['department' => 'Deactivate this department instead. It cannot be deleted while programmes or instructors depend on it.']);
        }
        try {
            $department->delete();
        } catch (QueryException) {
            return back()->withErrors(['department' => 'This department is still referenced and cannot be deleted.']);
        }

        return redirect()->route('departments.index')->with('success', 'Department deleted.');
    }

    /** @return array{dept_code:string,dept_name:string} */
    private function validateDepartment(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'dept_code' => ['required', 'string', 'max:20', 'alpha_dash:ascii', Rule::unique('department', 'dept_code')->ignore($department?->dept_id, 'dept_id')],
            'dept_name' => ['required', 'string', 'max:150', Rule::unique('department', 'dept_name')->ignore($department?->dept_id, 'dept_id')],
        ]);
    }
}
