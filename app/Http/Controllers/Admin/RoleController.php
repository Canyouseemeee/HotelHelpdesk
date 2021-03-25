<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    public function registered(){
        $users = User::all();
        $data = DB::table('users')
        ->select('id','name','image','usertypeid','username','logintype','departments.dmname','active','latitude','longitude')
        ->join('departments', 'users.departmentid', '=', 'departments.departmentid')
        ->get();
        return view('admin.register',compact('data'));
    }

    public function registeredcreate(Request $request){
        $department = Department::all();
        return view('admin.register-create',compact('department'));
    }

    public function registerstore(Request $request)
    {
        $user = new User;
        $user->id = $request->input('id');
        $user->name = $request->input('name');
        $user->departmentid = $request->input('departmentid');
        $user->usertypeid = $request->input('usertypeid');
        $user->logintype = $request->input('logintype');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        if($request->input('latitude') == null){
            $user->latitude = null;
        }else{
            $user->latitude = $request->input('latitude');
        }
        if($request->input('longitude') == null){
            $user->longitude = null;
        }else{
            $user->longitude = $request->input('longitude');
        }

        if ($request->hasFile('Image')) {
            $filename = $request->Image->getClientOriginalName();
            $file = time() . '.' . $filename;
            $user->image = $request->Image->storeAs('imagesprofile', $file, 'public');
            // dd($file);
        } else {
            $user->image = null;
        }
        // echo($user);
        $user->save();

        return redirect('/role-register')->with('status','เพิ่มข้อมูลพนักงานสำเร็จ');
    }

    public function changActive(Request $request)
    {
        $user = User::find($request->id);
        $user->active = $request->active;
        echo($user->active);
        $user->save();
        return response()->json(['success' => 'Status Change successfully']);
    }

    public function registeredit(Request $request,$id){
        $users = User::find($id);
        $department = Department::all();
        return view('admin.register-edit',compact('users','department'));
    }

    public function registerupdate(Request $request, $id){
        $users = User::find($id);
        $users->name = $request->input('name');
        $users->departmentid = $request->input('departmentid');
        $users->usertypeid = $request->input('usertypeid');
        $users->logintype = $request->input('logintype');
        $users->username = $request->input('username');
        $user->latitude = $request->input('latitude');
        $user->longitude = $request->input('longitude');
        if ($request->hasFile('image')) {
            $filename = $request->image->getClientOriginalName();
            $file = time() . '.' . $filename;
            $users->image = $request->image->storeAs('imagesprofile', $file, 'public');
            // dd($file);
        } else {
            $users->image = null;
        }
        $users->update();

        return redirect('/role-register')->with('status','อัพเดทข้อมูลพนักงงานสำเร็จ');
    }

    public function delete($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        // Session::flash('statuscode', 'error');
        return redirect()->with('danger', 'ลบข้อมูลพนักงงานสำเร็จ');
    }

    public function registerreset(Request $request, $id){
        $users = User::findOrFail($id);
        return view('admin.register-reset',compact('users'));
    }

    public function registerresetpassword(Request $request, $id){
        $users = User::findOrFail($id);
        $users->password = Hash::make($request->input('password'));
        $users->update();
        return redirect('/role-register')->with('status','Your Password is Reset');
    }


}
