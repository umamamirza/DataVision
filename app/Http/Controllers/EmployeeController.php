<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function index()
    {
        return view('employees');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'position']);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('photos', $filename, 'public');
            $data['photo'] = $path;
        }

        Employee::create($data);

        return response()->json([
            'success' => true,
            'redirect_url' => route('employees.index')
        ]);
    }
public function getData(Request $request)
{
    $search = $request->input('search')['value'] ?? null;
    $length = $request->input('length', 10);
    $start = $request->input('start', 0);
    $draw = $request->input('draw');
    $columns = [
        0 => 'id',
        1 => 'photo',
        2 => 'name',
        3 => 'email',
        4 => 'position',
    ];
    // Sorting
    $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
    $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $query = Employee::query();
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('position', 'like', "%{$search}%");
        });
    }
    $totalFiltered = $query->count();
    $totalRecords = Employee::count();
    $employees = $query
        ->orderBy($orderColumn, $orderDirection)
        ->skip($start)
        ->take($length)
        ->get();
    $data = [];
    foreach ($employees as $index => $employee) {
        $data[] = [
            'DT_RowIndex' => $start + $index + 1,
            'photo' => '<img src="' . asset('storage/' . $employee->photo) . '" width="50" height="50" class="rounded-circle">',
            'name' => $employee->name,
            'email' => $employee->email,
            'position' => $employee->position,
            'action' => '
                <a href="' . route('employees.edit', $employee->id) . '" class="btn btn-primary btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete-btn" data-id="' . $employee->id . '">Delete</button>
            '
        ];
    }

    return response()->json([
        'draw' => intval($draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalFiltered,
        'data' => $data,
    ]);
}



    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->only(['name', 'email', 'position']);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('photos', $filename, 'public');
            $data['photo'] = $path;
        }

        $employee->update($data);

        return response()->json([
            'success' => true,
            'redirect_url' => route('employees.index')
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return response()->json(['success' => true]);
    }
}
