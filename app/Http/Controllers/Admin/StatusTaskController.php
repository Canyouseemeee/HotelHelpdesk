<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issuespriority;
use App\Models\StatusTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StatusTaskController extends Controller
{
    public function index(){
        $statustask = StatusTask::all();
        return view('admin.statustask.index',compact('statustask'));
    }

    public function create(){
        return view('admin.statustask.create');
    }

    public function store(Request $request){
        $statustask = new StatusTask();
        $statustask->statustaskid = $request->input('statustaskid');
        $statustask->statustaskname = $request->input('statustaskname');
        $statustask->save();

        Session::flash('statuscode','success');
        return redirect('/statustask')->with('status','บันทึกข้อมูลประเภทงานสำเร็จ');
    }

    public function edit($statustaskid){
        $statustask = StatusTask::find($statustaskid);
        return view('admin.statustask.edit',compact('statustask'));
    }

    public function update(Request $request,$statustaskid){

        $statustask = StatusTask::find($statustaskid);
        $statustask->statustaskid = $request->input('statustaskid');
        $statustask->statustaskname = $request->input('statustaskname');
        $statustask->update();

        Session::flash('statuscode','success');
        return redirect('/statustask')->with('status','อัพเดทข้อมูลประเภทงานสำเร็จ');
    }

    public function delete($Roomid){
        $room = Issuespriority::findOrFail($Roomid);
        $room->delete();
        Session::flash('statuscode','error');
        return redirect('/room')->with('danger','Your room is Deleted');
    }

    public function changStatusRoom(Request $request)
    {
        $room = Room::find($request->Roomid);
        $room->Status = $request->Status;
        $room->update();
        return response()->json(['success' => 'Status Change successfully']);
    }
}
