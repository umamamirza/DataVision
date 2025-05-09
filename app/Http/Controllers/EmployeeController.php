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
        $filename = time().'_'.$request->file('photo')->getClientOriginalName();
        $path = $request->file('photo')->storeAs('photos', $filename, 'public');
        $data['photo'] = $path;
    }

    Employee::create($data);

    return response()->json([
        'success' => true,
        'redirect_url' => route('employees.index')
    ]);
}



// public function getData()
// {
//     return DataTables::of(Employee::query())
//         ->addIndexColumn()
//         ->editColumn('photo', function ($employee) {
//             $url = asset('storage/' . $employee->photo);
//             return "<img src='$url' width='50' height='50'/>";
//         })


//         ->addColumn('action', function ($row) {
//             $editUrl = route('employees.edit', $row->id);
//             return '
//                 <a href="'.$editUrl.'" class="btn btn-primary btn-sm">Edit</a>
//                 <button class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>
//             ';
//         })
//         ->rawColumns(['photo', 'action'])
//         ->make(true);
// }


public function getData(Request $request)
{
    $query = Employee::select(['id', 'name', 'email', 'position', 'photo']);

    return DataTables::of($query)
        ->filter(function ($query) use ($request) {
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
                });
            }
        })
        ->addIndexColumn()
        ->editColumn('photo', function ($employee) {
            $url = asset('storage/' . $employee->photo);
            return "<img src='$url' width='50' height='50'/>";
        })
        ->addColumn('action', function ($row) {
            $editUrl = route('employees.edit', $row->id);
            return '
                <a href="'.$editUrl.'" class="btn btn-primary btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>
            ';
        })
        ->rawColumns(['photo', 'action'])
        ->make(true);
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
        // Delete old photo
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        $filename = time().'_'.$request->file('photo')->getClientOriginalName();
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
