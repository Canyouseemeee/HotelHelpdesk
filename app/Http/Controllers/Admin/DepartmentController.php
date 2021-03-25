<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\TypeIssues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DepartmentController extends Controller
{
    public function index()
    {
        $department = Department::all();
        return view('admin.department.index', compact('department'));
    }

    public function create()
    {
        return view('admin.department.create');
    }

    public function store(Request $request)
    {
        // $this->validate(
        //     $request,
        //     array(
        //         'DmName' => 'required',
        //         'DmCode' => 'required',

        //     ),
        //     [
        //         'DmName.required' => 'You have enter Department Name',
        //         'DmCode.required' => 'You have enter Department CodeName',
        //         'DmTel.required' => 'You have enter Department Tel'
        //     ]
        // );

        $department = new Department();
        $department->dmname = $request->input('dmname');
        // $typeissues->DmCode = $request->input('DmCode');
        $department->save();

        Session::flash('statuscode', 'success');
        return redirect('/department')->with('status', 'บันทึกข้อมูลแผนกสำเร็จ');
    }

    public function edit($departmentid)
    {
        $department = Department::find($departmentid);
        return view('admin.department.edit', compact('department'));
    }

    public function update(Request $request, $departmentid)
    {
        // $this->validate(
        //     $request,
        //     array(
        //         'DmName' => 'required',
        //         'DmCode' => 'required',

        //     ),
        //     [
        //         'DmName.required' => 'You have enter Department Name',
        //         'DmCode.required' => 'You have enter Department CodeName',
        //     ]
        // );

        $department = Department::find($departmentid);
        $department->dmname = $request->input('dmname');
        $department->update();

        Session::flash('statuscode', 'success');
        return redirect('/department')->with('status', 'อัพเดทข้อมูลแผนกสำเร็จ');
    }

    public function delete($Typeissuesid)
    {
        $typeissues = Department::findOrFail($Typeissuesid);
        $typeissues->delete();
        Session::flash('statuscode', 'error');
        return redirect('/department')->with('danger', 'Your typeissues is Deleted');
    }

    public function changStatus(Request $request)
    {
        $department = Department::find($request->departmentid);
        $department->status = $request->Status;
        $department->update();
        // printf($request->Typeissuesid);
        return response()->json(['success' => 'อัพเดทข้อมูลแผนกสำเร็จ']);
    }
}
