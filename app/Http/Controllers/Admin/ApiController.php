<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\HtIssues;
use App\Models\Issues;
use App\Models\IssuesCheckin;
use App\Models\IssuesComment;
use App\Models\Loginlog;
use App\Models\MacAddress;
use App\Models\VersionApp;
use App\User;
use App\Models\CheckIn;
use App\Models\Task;
use App\Models\Solvework;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console as EnvironmentConsole;

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    return "$strYear-$strMonth-$strDay $strHour:$strMinute:$strSeconds";
}

function DateThai2($strDate)
{
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    $strHour = date("H", strtotime($strDate)) + 7;
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    if ($strHour < 10 && $strHour >= 0) {
        return "$strYear$strMonth$strDay 0$strHour$strMinute$strSeconds";
    } else {
        return "$strYear$strMonth$strDay$strHour$strMinute$strSeconds";
    }
}


class ApiController extends Controller
{
    

    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $username = $request->only('username');
        $userinfo = DB::table('users')
            ->select('*')
            ->where([['username', $username], ['active', 1]])
            ->get();

        $logintype = 0;
        foreach ($userinfo as $uinfo) {
            // echo $uinfo->name;
            // $isuser = 1;
            $userid = $uinfo->id;
            $logintype = $uinfo->logintype;
            $name = $uinfo->name;
            $usertypeid = $uinfo->usertypeid;
            $departmentid = $uinfo->departmentid;
            $latitude = $uinfo->latitude;
            $longitude = $uinfo->longitude;
            // $userprofile = array("id" => $uinfo->id, "logintype" => $logintype);
        }
        $department = DB::table('departments')
            ->select('*')
            ->where([['departmentid', $departmentid], ['status', 1]])
            ->get();

        foreach ($department as $dpm) {
            $dmname = $dpm->dmname;
        }

        if ($usertypeid == 1) {
            $usertype = "ADMIN";
        } elseif ($usertypeid == 2) {
            $usertype = "SUPERUSER";
        } elseif ($usertypeid == 3) {
            $usertype = "USER";
        }
        $token = openssl_random_pseudo_bytes(20);
        $token2 = bin2hex($token);
        $expires_at = DateThai2(now()->addHours(8));

        if ($logintype == 1) {
            if (Auth::loginUsingId($uinfo->id, TRUE)) {
                return response()->json([
                    'status' => 'Success',
                    'token' => $token2,
                    'usertype' => $usertype,
                    'logintype' => 'AD',
                    'userid' => $userid,
                    'input' => $username,
                    'name' => $name,
                    'expired_at' => $expires_at,
                    'department' => $dmname,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
            }
        } else if ($logintype == 0) {
            if ($token = Auth::attempt($input)) {
                return response()->json([
                    'status' => 'Success',
                    'token' => $token2,
                    'usertype' => $usertype,
                    'logintype' => 'DB',
                    'userid' => $userid,
                    'input' => $username,
                    'name' => $name,
                    'expired_at' => $expires_at,
                    'department' => $dmname,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
            }
        }
        if (!$token = Auth::attempt($input)) {
            return response()->json([
                'status' => 'Faild',
                'message' => 'Login Faild',
            ], 401);
        }
    }

    public function loginad(Request $request)
    {
        $input = $request->only('username');
        $userinfo = DB::table('users')
            ->select('*')
            ->where([['username', $input], ['active', 1]])
            ->get();

        $logintype = 0;
        foreach ($userinfo as $uinfo) {
            // echo $uinfo->name;
            $isuser = 1;
            $logintype = $uinfo->logintype;
            $userprofile = array("id" => $uinfo->id, "logintype" => $logintype);
        }
        $token = openssl_random_pseudo_bytes(20);
        $token2 = bin2hex($token);
        if (!Auth::loginUsingId($uinfo->id, TRUE)) {
            return response()->json([
                'status' => 'Faild',
                'message' => 'Login Faild',
            ], 401);
        }
        $expires_at = DateThai(now()->addHour(1));

        return response()->json([
            'status' => 'Success',
            'token' => $token2,
            // 'token' => $jwt_token,
            'input' => $input,
            'expires_at' => $expires_at
        ]);
    }

    public function checktoken(Request $request)
    {
        $_token = $request->input('token');

        return response()->json([
            'action' => 'checktoken',
            'status' => 'Success'
        ]);
    }

    public function postcheckin(Request $request)
    {
        $userid = $request->input('userid');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $checkin = new CheckIn();
        $checkin->userid = $userid;
        $checkin->date_start = DateThai(Carbon::now());
        $checkin->date_end = null;
        $checkin->date_in = Carbon::today();
        $checkin->status = 1;
        $checkin->latitude = $latitude;
        $checkin->longitude = $longitude;
        $checkin->created_at = DateThai(Carbon::now());
        $checkin->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $checkin->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $checkin->file = null;
        }

        $checkin->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function postcheckout(Request $request)
    {
        $checkinid = $request->input('checkinid');

        $checkin = CheckIn::find($checkinid);
        $checkin->date_end =  DateThai(Carbon::now());
        $checkin->status = 2;
        $checkin->updated_at = DateThai(Carbon::now());

        $checkin->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getcheckin(Request $request)
    {
        $userid = $request->input('userid');
        $data = DB::table('checkin_work')
            ->select(
                'checkinid',
                'users.name',
                'date_start',
                'date_end',
                'status',
                'file',
                'checkin_work.latitude',
                'checkin_work.longitude',
            )
            ->join('users', 'checkin_work.userid', '=', 'users.id')
            ->join('statuscheckin', 'checkin_work.status', '=', 'statuscheckin.statusid')
            ->where('checkin_work.date_in', Carbon::now()->toDateString())
            ->where('users.id',$userid)
            ->orderBy('checkin_work.checkinid', 'DESC')
            ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function gethistorycheckin(Request $request)
    {
        $userid = $request->input('userid');
        $data = DB::table('checkin_work')
            ->select(
                'checkinid',
                'users.name',
                'date_start',
                'date_end',
                'status',
                'file',
                'checkin_work.latitude',
                'checkin_work.longitude',
            )
            ->join('users', 'checkin_work.userid', '=', 'users.id')
            ->join('statuscheckin', 'checkin_work.status', '=', 'statuscheckin.statusid')
            ->where('checkin_work.date_in', Carbon::now()->toDateString())
            ->where('users.id',$userid)
            ->orderBy('checkin_work.checkinid', 'DESC')
            ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function gethistorybetweencheckin(Request $request)
    {
        $userid = $request->input('userid');
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');

        $data = DB::table('checkin_work')
            ->select(
                'checkinid',
                'users.name',
                'date_start',
                'date_end',
                'status',
                'file',
                'checkin_work.latitude',
                'checkin_work.longitude',
            )
            ->join('users', 'checkin_work.userid', '=', 'users.id')
            ->join('statuscheckin', 'checkin_work.status', '=', 'statuscheckin.statusid')
            ->whereBetween('checkin_work.date_in', [$fromdate, $todate])
            ->where('users.id',$userid)
            ->orderBy('checkin_work.checkinid', 'DESC')
            ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function posttask(Request $request)
    {
        $userid = $request->input('userid');
        $subject = $request->input('subject');
        $description = $request->input('description');
        $assignment = $request->input('assignment');
        $duedate = $request->input('duedate');
        $departmentid = $request->input('departmentid');

        $task = new Task();
        $task->createtask = $userid;
        $task->subject = $subject;
        $task->description = $description;
        $task->statustask = 1;
        $task->departmentid = $departmentid;
        $task->date_in = DateThai(Carbon::now()->toDateString());
        $task->assignment = $assignment;
        $task->assign_date = DateThai(Carbon::now());
        $task->due_date = $duedate;
        $task->close_date = null;
        $task->created_at = DateThai(Carbon::now());
        $task->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $task->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $task->file = null;
        }

        $task->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function postsubmittask(Request $request)
    {
        $userid = $request->input('userid');
        $taskid = $request->input('taskid');

        $task = Task::find($taskid);
        $task->statustask = 3;
        $task->close_date = Carbon::today();
        $task->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $task->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $task->file = null;
        }

        $task->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updatetask(Request $request)
    {
        $taskid = $request->input('taskid');
        $userid = $request->input('userid');
        $subject = $request->input('subject');
        $description = $request->input('description');
        $assignment = $request->input('assignment');
        $duedate = $request->input('duedate');
        $departmentid = $request->input('departmentid');

        $task = Task::find($taskid);
        $task->createtask = $userid;
        $task->subject = $subject;
        $task->description = $description;
        // $task->statustask = 1;
        $task->departmentid = $departmentid;
        $task->assignment = $assignment;
        // $task->assign_date = DateThai(Carbon::now());
        $task->due_date = $duedate;
        // $task->created_at = DateThai(Carbon::now());
        $task->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $task->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $task->file = null;
        }

        $task->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function gettask(Request $request)
    {
        $userid = $request->input('userid');

        $data = DB::table('task')
        ->select(
            'taskid',
            'createtask',
            'subject',
            'description',
            'statustask.statustaskid',
            'task.departmentid',
            'departments.dmname',
            'assignment',
            'users.name',
            'file',
            'assign_date',
            'due_date',
        )
        ->join('users', 'task.createtask', '=', 'users.id')
        ->join('statustask', 'task.statustask', '=', 'statustask.statustaskid')
        ->join('departments', 'task.departmentid', '=', 'departments.departmentid')
        ->where('users.id',$userid)
        ->orderBy('task.taskid', 'DESC')
        ->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function getassigntask(Request $request)
    {
        $userid = $request->input('userid');

        $data = DB::table('task')
        ->select(
            'taskid',
            'createtask',
            'subject',
            'description',
            'statustask.statustaskid',
            'task.departmentid',
            'departments.dmname',
            'assignment',
            'users.name',
            'file',
            'assign_date',
            'due_date',
        )
        ->join('users', 'task.assignment', '=', 'users.id')
        ->join('statustask', 'task.statustask', '=', 'statustask.statustaskid')
        ->join('departments', 'task.departmentid', '=', 'departments.departmentid')
        ->where('task.assignment',$userid)
        ->whereIn('task.statustask',array(1,2))
        ->orderBy('task.taskid', 'DESC')
        ->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function gethistoryassigntask(Request $request)
    {
        $userid = $request->input('userid');

        $data = DB::table('task')
        ->select(
            'taskid',
            'createtask',
            'subject',
            'description',
            'statustask.statustaskid',
            'task.departmentid',
            'departments.dmname',
            'assignment',
            'users.name',
            'file',
            'assign_date',
            'due_date',
        )
        ->join('users', 'task.assignment', '=', 'users.id')
        ->join('statustask', 'task.statustask', '=', 'statustask.statustaskid')
        ->join('departments', 'task.departmentid', '=', 'departments.departmentid')
        ->where('task.assignment',$userid)
        ->where('task.close_date', Carbon::now()->toDateString())
        ->where('task.statustask',3)
        ->orderBy('task.taskid', 'DESC')
        ->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function gethistorybetweenassigntask(Request $request)
    {
        $userid = $request->input('userid');
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');

    
        $data = DB::table('task')
        ->select(
            'taskid',
            'createtask',
            'subject',
            'description',
            'statustask.statustaskid',
            'task.departmentid',
            'departments.dmname',
            'assignment',
            'users.name',
            'file',
            'assign_date',
            'due_date',
        )
        ->join('users', 'task.assignment', '=', 'users.id')
        ->join('statustask', 'task.statustask', '=', 'statustask.statustaskid')
        ->join('departments', 'task.departmentid', '=', 'departments.departmentid')
        ->where('task.assignment',$userid)
        ->whereBetween('task.close_date', [$fromdate, $todate])
        ->where('task.statustask',3)
        ->orderBy('task.taskid', 'DESC')
        ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function poststatustask(Request $request)
    {
        $taskid = $request->input('taskid');

        $task = Task::find($taskid);
        $task->statustask = 2;
        $task->updated_at = DateThai(Carbon::now());


        $task->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getdepartment()
    {
        $data = DB::table('departments')
            ->select(
                'departmentid',
                'dmname',
                
            )->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function getuser(Request $request)
    {
        $departmentid = $request->input('departmentid');
        $data = DB::table('users')
            ->select(
                'id',
                'name', 
            )
            ->join('departments', 'users.departmentid', '=', 'departments.departmentid')
            ->where('departments.departmentid',$departmentid)
            ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function getuserhis()
    {
        $data = DB::table('users')
        ->select(
            'id',
            'name', 
        )
        ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function postretask(Request $request)
    {
        $taskid = $request->input('taskid');
        $userid = $request->input('userid');
        $subject = $request->input('subject');
        $assignment = $request->input('assignment');
        $duedate = $request->input('duedate');
        $departmentid = $request->input('departmentid');
        
        $solvework = new Solvework();
        $solvework->taskid = $taskid;
        $solvework->createsolvework = $userid;
        $solvework->subject = $subject;
        $solvework->statussolvework = 1;
        $solvework->departmentid = $departmentid;
        $solvework->assignment = $assignment;
        $solvework->assign_date = DateThai(Carbon::now());
        $solvework->due_date = $duedate;
        $solvework->close_date = null;
        $solvework->created_at = DateThai(Carbon::now());
        $solvework->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $solvework->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $solvework->file = null;
        }

        $solvework->save();

        // $task = Task::find($taskid);
        // $task->statustask = 2;

        // $task->update();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updatesolve(Request $request)
    {
        $solveworkid = $request->input('solveworkid');
        $subject = $request->input('subject');
        $duedate = $request->input('duedate');
        
        $solvework = Solvework::find($solveworkid);
        $solvework->subject = $subject;
        $solvework->due_date = $duedate;
        $solvework->updated_at = DateThai(Carbon::now());

        $solvework->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function countsolvework(Request $request){
        $taskid = $request->input('taskid');
        $comment = DB::table('solvework')
            ->select('*')
            ->join('task', 'task.taskid', '=', 'solvework.taskid')
            ->where('solvework.taskid',$taskid)
            ->count();
        return response()->json($comment);
    }

    public function getsolvework(Request $request)
    {
        $taskid = $request->input('taskid');

        $data = DB::table('solvework')
        ->select(
            'solveworkid',
            'solvework.taskid',
            'createsolvework',
            'solvework.subject',
            'statussolvework.statussolveworkid',
            'solvework.departmentid',
            'departments.dmname',
            'solvework.assignment',
            'users.name',
            'solvework.file',
            'solvework.assign_date',
            'solvework.due_date',
            'solvework.close_date',
        )
        ->join('task', 'task.taskid', '=', 'solvework.taskid')
        ->join('users', 'solvework.createsolvework', '=', 'users.id')
        ->join('statussolvework', 'solvework.statussolvework', '=', 'statussolvework.statussolveworkid')
        ->join('departments', 'solvework.departmentid', '=', 'departments.departmentid')
        ->where('solvework.taskid',$taskid)
        ->orderBy('solvework.taskid', 'DESC')
        ->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function postsubmitsolvework(Request $request)
    {
        $taskid = $request->input('taskid');
        $solveworkid = $request->input('solveworkid');

        $solvework = Solvework::find($solveworkid);
        $solvework->statussolvework = 2;
        $solvework->close_date = Carbon::today();
        $solvework->updated_at = DateThai(Carbon::now());

        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $file = time() . '.' . $filename;
            $solvework->file = $request->file->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $solvework->file = null;
        }


        $solvework->update();

        // $task = Task::find($taskid);
        // $task->statussolvework = 3;
        // $task->updated_at = DateThai(Carbon::now());
        // $task->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function poststatussolve(Request $request)
    {
        $taskid = $request->input('taskid');


        $task = Task::find($taskid);
        $task->statustask = 3;
        $task->close_date = Carbon::today();
        $task->updated_at = DateThai(Carbon::now());
        $task->update();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function gethistoryassignsolve(Request $request)
    {
        $userid = $request->input('userid');

        $data = DB::table('solvework')
        ->select(
            'solveworkid',
            'solvework.taskid',
            'createsolvework',
            'solvework.subject',
            'statussolvework.statussolveworkid',
            'solvework.statussolvework',
            'solvework.departmentid',
            'departments.dmname',
            'solvework.assignment',
            'users.name',
            'solvework.file',
            'solvework.assign_date',
            'solvework.due_date',
            'solvework.close_date',
        )
        ->join('task', 'task.taskid', '=', 'solvework.taskid')
        ->join('users', 'solvework.createsolvework', '=', 'users.id')
        ->join('statussolvework', 'solvework.statussolvework', '=', 'statussolvework.statussolveworkid')
        ->join('departments', 'solvework.departmentid', '=', 'departments.departmentid')
        ->where('solvework.assignment',$userid)
        ->where('solvework.close_date', Carbon::now()->toDateString())
        ->where('solvework.statussolvework',2)
        ->orderBy('task.taskid', 'DESC')
        ->get();

        // echo($data->createtask);

        // echo(Carbon::now());

        return response()->json($data);
    }

    public function gethistorybetweenassignsolve(Request $request)
    {
        $userid = $request->input('userid');
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');
        $taskid = $request->input('taskid');

    
        $data = DB::table('solvework')
        ->select(
            'solveworkid',
            'solvework.taskid',
            'createsolvework',
            'solvework.subject',
            'statussolvework.statussolveworkid',
            'solvework.statussolvework',
            'solvework.departmentid',
            'departments.dmname',
            'solvework.assignment',
            'users.name',
            'solvework.file',
            'solvework.assign_date',
            'solvework.due_date',
            'solvework.close_date',
        )
        ->join('task', 'task.taskid', '=', 'solvework.taskid')
        ->join('users', 'solvework.createsolvework', '=', 'users.id')
        ->join('statussolvework', 'solvework.statussolvework', '=', 'statussolvework.statussolveworkid')
        ->join('departments', 'solvework.departmentid', '=', 'departments.departmentid')
        ->where('solvework.assignment',$userid)
        ->where('solvework.taskid',$taskid)
        ->whereBetween('solvework.close_date', [$fromdate, $todate])
        ->where('solvework.statussolvework',2)
        ->orderBy('solvework.solveworkid', 'DESC')
        ->get();

            // echo(Carbon::now());

        return response()->json($data);
    }

    public function download(){
        $path=public_path('storage/files/1616421854.04_Fun_little_kid_game2.pdf');
        return response()->download($path);
    }

    // public function Closed()
    // {
    //     $demodata = DB::table('issues_tracker')
    //         ->select(
    //             'issues.Issuesid',
    //             'issues_tracker.TrackName',
    //             'issues_tracker.SubTrackName',
    //             'issues_tracker.Name',
    //             'issues_status.ISSName',
    //             'issues_priority.ISPName',
    //             'issues.Createby',
    //             'users.name as Assignment',
    //             'issues.UpdatedBy',
    //             'issues.Subject',
    //             'issues.Tel',
    //             'issues.Comname',
    //             'issues.Informer',
    //             'issues.Description',
    //             'issues.created_at',
    //             'issues.updated_at',
    //             'department.DmName',
    //             'issues.ClosedBy',
    //             'issues_logs.create_at'
    //         )
    //         ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
    //         ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
    //         ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('department', 'issues.Departmentid', '=', 'department.Departmentid')
    //         ->join('issues_logs', 'issues.Issuesid', '=', 'issues_logs.Issuesid')
    //         ->join('users', 'issues.Assignment', '=', 'users.id')
    //         ->where([['issues.Statusid', 2], ['issues_logs.Action', 'Closed']])
    //         ->orderBy('issues.Issuesid', 'DESC')
    //         ->limit(15)
    //         ->get();
    //     $htissues = DB::table('htissues')
    //         ->select('htissues.Issuesid', 'NoRoom', 'ISSName', 'Typename', 'users.name as Assignment', 'htissues.Subject', 'htissues.Description', 
    //         'htissues.Createby', 'htissues.Updatedby', 'htissues.created_at', 'htissues.updated_at')
    //         ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('room', 'htissues.Roomid', '=', 'room.Roomid')
    //         ->join('typeissues', 'htissues.Typeissuesid', '=', 'typeissues.Typeissuesid')
    //         ->join('users', 'htissues.Assignment', '=', 'users.id')
    //         ->join('issues_logs', 'htissues.Issuesid', '=', 'issues_logs.Issuesid')
    //         ->where([['htissues.Statusid', 2], ['issues_logs.Action', 'Closed']])
    //         ->groupBy('htissues.Issuesid')
    //         ->orderBy('htissues.Issuesid', 'DESC')
    //         ->get();

    //     return response()->json($htissues);
    // }

    // public function New()
    // {
    //     $htissues = DB::table('htissues')
    //         ->select('Issuesid', 'NoRoom', 'ISSName', 'Typename', 'users.name as Assignment', 'htissues.Subject', 'htissues.Description', 
    //         'htissues.Createby', 'htissues.Updatedby', 'htissues.created_at', 'htissues.updated_at')
    //         ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('room', 'htissues.Roomid', '=', 'room.Roomid')
    //         ->join('typeissues', 'htissues.Typeissuesid', '=', 'typeissues.Typeissuesid')
    //         ->join('users', 'htissues.Assignment', '=', 'users.id')
    //         ->where('htissues.Statusid', 1)
    //         ->orderBy('Issuesid', 'DESC')
    //         ->get();
    //     return response()->json($htissues);
    // }

    // public function Progress()
    // {
    //     $demodata = DB::table('issues_tracker')
    //         ->select(
    //             'issues.Issuesid',
    //             'issues_tracker.TrackName',
    //             'issues_tracker.SubTrackName',
    //             'issues_tracker.Name',
    //             'ISSName',
    //             'ISPName',
    //             'issues.Createby',
    //             'users.name as Assignment',
    //             'issues.UpdatedBy',
    //             'issues.Subject',
    //             'issues.Tel',
    //             'issues.Comname',
    //             'issues.Informer',
    //             'issues.Description',
    //             'issues.created_at',
    //             'issues.updated_at',
    //             'DmName'
    //         )
    //         ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
    //         ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
    //         ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('department', 'issues.Departmentid', '=', 'department.Departmentid')
    //         ->join('issues_logs', 'issues.Issuesid', '=', 'issues_logs.Issuesid')
    //         ->join('users', 'issues.Assignment', '=', 'users.id')
    //         ->where([['issues.Statusid', 6], ['issues_logs.Action', 'Updated']])
    //         ->groupBy('issues.Issuesid')
    //         ->orderBy('issues.Issuesid', 'DESC')
    //         ->get();
    //     $htissues = DB::table('htissues')
    //         ->select('htissues.Issuesid', 'NoRoom', 'ISSName', 'Typename', 'users.name as Assignment', 'htissues.Subject', 'htissues.Description', 
    //         'htissues.Createby', 'htissues.Updatedby', 'htissues.created_at', 'htissues.updated_at')
    //         ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('room', 'htissues.Roomid', '=', 'room.Roomid')
    //         ->join('typeissues', 'htissues.Typeissuesid', '=', 'typeissues.Typeissuesid')
    //         ->join('users', 'htissues.Assignment', '=', 'users.id')
    //         ->join('issues_logs', 'htissues.Issuesid', '=', 'issues_logs.Issuesid')
    //         ->where([['htissues.Statusid', 3], ['issues_logs.Action', 'Updated']])
    //         ->groupBy('htissues.Issuesid')
    //         ->orderBy('Issuesid', 'DESC')
    //         ->get();

    //     return response()->json($htissues);
    // }

    // public function Getissuesuser(Request $request)
    // {
    //     $_iduser = $request->input('iduser');
    //     $htissues = DB::table('htissues')
    //         ->select('Issuesid', 'NoRoom', 'ISSName', 'Typename', 'users.name as Assignment', 'htissues.Subject', 'htissues.Description', 
    //         'htissues.Createby', 'htissues.Updatedby', 'htissues.created_at', 'htissues.updated_at')
    //         ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
    //         ->join('room', 'htissues.Roomid', '=', 'room.Roomid')
    //         ->join('typeisaasues', 'htissues.Typeissuesid', '=', 'typeissues.Typeissuesid')
    //         ->join('users', 'htissues.Assignment', '=', 'users.id')
    //         ->where([['htissues.Date_In', now()->toDateString()],['htissues.Assignment',$_iduser]])
    //         ->whereIn('htissues.Statusid',array(1,3))
    //         ->orderBy('Issuesid', 'DESC')
    //         ->get();
    //     return response()->json($htissues);
    // }

    // public function poststatus(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $_user = $request->input('user');

    //     $checkin = new IssuesCheckin();
    //     $checkin->Issuesid = $_issuesid;
    //     $checkin->Status = 1;
    //     $checkin->Detail = '';
    //     $checkin->Createby = $_user;
    //     $checkin->created_at = DateThai(now());
    //     $checkin->updated_at = DateThai(now());
    //     $checkin->save();

    //     $htissues = HtIssues::find($_issuesid);
    //     $htissues->Statusid = 3;
    //     $htissues->update();

    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }

    // public function updateclosedstatus(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $_user = $request->input('user');
    //     $_detail = $request->input('detail');
    //     $_checkid = $request->input('checkin');

    //     $checkinid = DB::table('issues_checkin')
    //         ->select('*')
    //         ->where('Issuesid', $_issuesid)
    //         ->get();

    //     $checkin = IssuesCheckin::find($_checkid);
    //     $checkin->Status = 2;
    //     $checkin->Detail = $_detail;
    //     $checkin->Updateby = $_user;
    //     $checkin->updated_at = DateThai(now());
    //     $checkin->update();

    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }

    // public function updatekeepstatus(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $_user = $request->input('user');
    //     $_detail = $request->input('detail');
    //     $_checkid = $request->input('checkin');

    //     $checkinid = DB::table('issues_checkin')
    //         ->select('*')
    //         ->where('Issuesid', $_issuesid)
    //         ->get();

    //     $checkin = IssuesCheckin::find($_checkid);
    //     $checkin->Status = 3;
    //     $checkin->Detail = $_detail;
    //     $checkin->Updateby = $_user;
    //     $checkin->updated_at = DateThai(now());
    //     $checkin->update();

    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }

    // public function getstatus(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');

    //     $checkin = DB::table('issues_checkin')
    //         ->select('*')
    //         ->where('Issuesid', $_issuesid)
    //         ->get();
    //     // $checkin = IssuesCheckin::all();
    //     return response()->json($checkin);
    // }

    // public function getcountComment(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $comment = DB::table('issues_comment')
    //         ->select('*')
    //         ->where('Issuesid', $_issuesid)
    //         ->count();
    //     return response()->json(['count' => $comment]);
    // }

    // public function getComment(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $comment = DB::table('issues_comment')
    //         ->select('*')
    //         ->where('Issuesid', $_issuesid)
    //         ->get();
    //     return response()->json($comment);
    // }

    // public function postComment(Request $request)
    // {
    //     $_issuesid = $request->input('issuesid');
    //     $_comment = $request->input('comment');
    //     $_user = $request->input('user');


    //     $comment = new IssuesComment();
    //     $comment->Issuesid = $_issuesid;
    //     $comment->Type = 1;
    //     $comment->Comment = $_comment;
    //     $comment->Createby = $_user;
    //     $comment->created_at = DateThai(now());
    //     $comment->updated_at = DateThai(now());

    //     if ($request->hasFile('image')) {
    //         $filename = $request->image->getClientOriginalName();
    //         $file = time() . '.' . $filename;
    //         $comment->Image = $request->image->storeAs('images', $file, 'public');
    //         // dd($file);
    //         // echo($file);
    //     } else {
    //         $comment->Image = null;
    //     }

    //     $comment->save();

    //     return response()->json(
    //         // 'status' => 'success'
    //     );
    // }

    // public function postStatusComment(Request $request)
    // {
    //     $_commentid = $request->input('commentid');

    //     $comment = IssuesComment::find($_commentid);
    //     $comment->Status = 0;
    //     $comment->update();

    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }

    // public function postlogin(Request $request)
    // {
    //     $_username = $request->input('username');
    //     $_deviceid = $request->input('deviceid');
    //     $_ip = $request->input('ip');
    //     $_token = $request->input('token');
    //     $_expired = DateThai2(now()->addHours(8));

    //     $data = DB::table('users')
    //         ->select('id')
    //         ->where('username', $_username)
    //         ->get();

    //     $image = DB::table('users')
    //         ->select('image')
    //         ->where('username', $_username)
    //         ->get();

    //     $Loginlog = new Loginlog();
    //     $Loginlog->Deviceid = $_deviceid;
    //     $Loginlog->Userid = $data[0]->id;
    //     $Loginlog->Token = $_token;
    //     $Loginlog->Ip = $_ip;
    //     $Loginlog->expired = DateThai(now()->addHours(8));
    //     $Loginlog->created_at = DateThai(now());
    //     $Loginlog->updated_at = DateThai(now());
    //     $Loginlog->save();

    //     return response()->json([
    //         'status' => 'Success',
    //         'data' => $data,
    //         'deviceid' => $_deviceid,
    //         'ip' => $_ip,
    //         'token' => $_token,
    //         'expired' => $_expired,
    //         'image' => $image
    //     ]);
    // }

    // public function delete(Request $request)
    // {
    //     $_token = $request->input('token');

    //     $data = DB::table('loginlog')
    //         ->select('Loginid')
    //         ->where([['Token', $_token], ['expired', '<', DateThai(now())]])
    //         ->get();
    //     // $Loginlog = Loginlog::findOrFail($data[0]->Loginid);
    //     // $Loginlog->delete();
    //     return response()->json([
    //         'action' => 'Delete',
    //         'status' => 'Success'
    //     ]);
    // }

    // public function Deviceid(Request $request)
    // {
    //     $_deviceid = $request->input('deviceid');

    //     $data = DB::table('deviceinfo')
    //         ->select('deviceid')
    //         ->where('deviceid', $_deviceid)
    //         ->get();

    //     return response()->json([
    //         'status' => 'Success',
    //         'deviceid' => $data
    //     ]);
    // }

    // public function lastedVersion()
    // {
    //     $VersionApp = DB::table('version_app')
    //         ->select('AppVersion',)
    //         ->max('AppVersion');
    //     $url = DB::table('version_app')
    //         ->select('url',)
    //         ->max('url');

    //     return response()->json([
    //         'version' => $VersionApp,
    //         'url' => $url
    //     ]);
    // }

    // public function Appointments()
    // {
    //     $Appointments = DB::table('appointments')
    //         ->select('*',)
    //         ->where([['Status', '1'], ['Issuesid', '>', 0]])
    //         ->whereBetween('Date', [DateThai(now()), DateThai(now()->addDay(7))])
    //         ->get();

    //     return response()->json($Appointments);
    // }

    // public function Appointmentlist(Request $request)
    // {
    //     $temp = $request->input('temp');

    //     $data = DB::table('appointments')
    //         ->select('*')
    //         ->where('Uuid', $temp)
    //         ->orderBy('Appointmentsid', 'DESC')
    //         ->get();

    //     return response()->json($data);
    // }

    // public function Commentlist(Request $request)
    // {
    //     $temp = $request->input('temp');

    //     $data = DB::table('issues_comment')
    //         ->select(
    //             'users.image',
    //             'issues_comment.Commentid',
    //             'issues_comment.Issuesid',
    //             'issues_comment.Status',
    //             'issues_comment.Type',
    //             'issues_comment.Comment',
    //             'issues_comment.Image',
    //             'issues_comment.Uuid',
    //             'issues_comment.Createby',
    //             'issues_comment.Updateby',
    //             'issues_comment.created_at',
    //             'issues_comment.updated_at'
    //         )
    //         ->join('users', 'issues_comment.Createby', '=', 'users.name')
    //         ->where('Uuid', $temp)
    //         ->orderBy('Commentid', 'DESC')
    //         ->get();
    //     foreach ($data as $row) {
    //         $createbycomment = $row->Createby;
    //     }

    //     return response()->json($data);
    // }

    // public function CommentlistStatus(Request $request)
    // {
    //     $Commentid = $request->input('commentid');
    //     // echo($Commentid);
    //     $data = DB::table('issues_comment')
    //         ->select('*')
    //         ->where('Commentid', $Commentid)
    //         ->get();
    //     foreach ($data as $row) {
    //         $cid = $row->Commentid;
    //         // echo($row->Commentid);
    //     }
    //     $comments = IssuesComment::find($cid);
    //     $comments->Status = 0;
    //     $comments->update();

    //     return response()->json($comments);
    // }
}
