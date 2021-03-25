<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StatusUserController extends Controller
{
    public function index(){
        $statususer = StatusUser::all();
        return view('admin.statususer.index',compact('statususer'));
    }

    public function create(){
        return view('admin.statususer.create');
    }

    public function store(Request $request){
        $this->validate($request, 
        array(
            'typename' => 'required' ,

        ),[
            'typename.required' => 'กรุณากรอกข้อมูลประเภทพนักงาน',
        ]);

        $statususer = new StatusUser();
        $statususer->typename = $request->input('typename');
        $statususer->save();

        Session::flash('statuscode','success');
        return redirect('/statususer')->with('status','บันทึกข้อมูลประเภทพนักงานสำเร็จ');
    }

    public function edit($usertypeid){
        $statususer = StatusUser::find($usertypeid);
        return view('admin.statususer.edit',compact('statususer'));
    }

    public function update(Request $request,$usertypeid){
        $this->validate($request, 
        array(
            'typename' => 'required' ,

        ),[
            'typename.required' => 'กรุณากรอกข้อมูลประเภทพนักงาน',
        ]);

        $statususer = StatusUser::find($usertypeid);
        $statususer->typename = $request->input('typename');
        $statususer->update();

        Session::flash('statuscode','success');
        return redirect('/statususer')->with('status','อัพเดทข้อมูลประเภทพนักงานสำเร็จ');
    }

    public function delete($Statusrid){
        $issuesstatus = StatusUser::findOrFail($Statusrid);
        $issuesstatus->delete();
        Session::flash('statuscode','error');
        return redirect('/status')->with('danger','Your Data is Deleted');
    }
}
