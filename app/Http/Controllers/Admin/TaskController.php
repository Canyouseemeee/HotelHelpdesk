<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FilterExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\Department;
use App\Models\Task;
use App\Models\IssuesComment;
use App\Models\IssuesLogs;
use App\Models\Issuespriority;
use App\Models\Issuesstatus;
use App\Models\Issuestracker;
use App\Models\HtIssues;
use App\Models\Room;
use App\Models\TypeIssues;
use App\User;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    return "$strYear-$strMonth-$strDay $strHour:$strMinute:$strSeconds";
}

function DateThai2($strDate)
{
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate)) + 7;
    $strMinute = date("i", strtotime($strDate)) - 1;
    $strSeconds = date("s", strtotime($strDate));
    if ($strMonth < 10 && $strMinute < 10) {
        return "$strYear-0$strMonth-$strDay $strHour:0$strMinute:$strSeconds";
    } else {
        return "$strYear-$strMonth-$strDay $strHour:$strMinute:$strSeconds";
    }
}

class TaskController extends Controller
{
    public function index()
    {
        $task = DB::table('task')
        ->select('taskid', 'createtask', 'subject', 'users.name', 'due_date')
        ->join('statustask', 'statustask.statustaskid', '=', 'task.statustask')
        ->join('users', 'users.id', '=', 'task.assignment')
        ->where([['statustask.statustaskid', 1]])
        ->orderBy('taskid', 'DESC')
        ->get();
        $between = null;
        $fromdate = null;
        $todate = null;
        $data = null;
        $Uuidapp = Str::uuid()->toString();
        return view('admin.tasks.index', compact(['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data'], ['task']));
    }

    public function getReport(Request $request)
    {
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');
        if ($request->isMethod('post')) {
            $between = DB::table('task')
                ->select('taskid', 'createtask', 'subject', 'users.name', 'due_date')
                ->join('statustask', 'statustask.statustask', '=', 'task.statustask')
                ->join('users', 'users.id', '=', 'task.assignment')
                ->where([['task.statustask', 1]])
                ->whereBetween('issues.Date_In', [$fromdate, $todate])
                ->orderBy('Issuesid', 'DESC')
                ->get();
            $data = DB::table('issues_tracker')
                ->select('Issuesid', 'issues_tracker.TrackName', 'ISSName', 'ISPName', 'Createby', 'Subject', 'issues.updated_at')
                ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
                ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
                ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
                ->where('issues.Statusid', 1)
                ->whereBetween('issues.Date_In', [$fromdate, $todate])
                ->orderBy('Issuesid', 'DESC')
                ->count();
            $htissues = DB::table('htissues')
                ->select('Issuesid', 'ISSName', 'Createby', 'Subject', 'htissues.updated_at')
                ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
                ->where([['htissues.Statusid', 1], ['htissues.Date_In', now()->toDateString()]])
                ->orderBy('Issuesid', 'DESC')
                ->get();
        } else {
            $between = null;
            $data = null;
        }
        $issues = null;
        $Uuidapp = Str::uuid()->toString();
        return view('admin.issues.index', compact(['issues'], ['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data'], ['htissues']));
    }

    public function progress()
    {
        $task = DB::table('task')
        ->select('taskid', 'createtask', 'subject', 'users.name', 'due_date')
        ->join('statustask', 'statustask.statustaskid', '=', 'task.statustask')
        ->join('users', 'users.id', '=', 'task.assignment')
        ->where([['statustask.statustaskid', 2]])
        ->orderBy('taskid', 'DESC')
        ->get();
        $between = null;
        $fromdate = null;
        $todate = null;
        $data = null;
        $Uuidapp = Str::uuid()->toString();
        return view('admin.tasks.progress', compact(['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data'], ['task']));
    }

    public function getReportprogress(Request $request)
    {
        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');
        if ($request->isMethod('post')) {
            $between = DB::table('issues_tracker')
                ->select('Issuesid', 'issues_tracker.TrackName', 'ISSName', 'ISPName', 'Createby', 'Subject', 'issues.updated_at')
                ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
                ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
                ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
                ->where('issues.Statusid', 3)
                ->whereBetween('issues.Date_In', [$fromdate, $todate])
                ->orderBy('Issuesid', 'DESC')
                ->get();
            $data = DB::table('issues_tracker')
                ->select('Issuesid', 'issues_tracker.TrackName', 'ISSName', 'ISPName', 'Createby', 'Subject', 'issues.updated_at')
                ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
                ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
                ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
                ->where('issues.Statusid', 3)
                ->whereBetween('issues.Date_In', [$fromdate, $todate])
                ->orderBy('Issuesid', 'DESC')
                ->count();
            $htissues = DB::table('htissues')
                ->select('Issuesid', 'ISSName', 'Createby', 'Subject', 'htissues.updated_at')
                ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
                ->where([['htissues.Statusid', 3], ['htissues.Date_In', now()->toDateString()]])
                ->orderBy('Issuesid', 'DESC')
                ->get();
        } else {
            $between = null;
            $data = null;
        }
        $issues = null;
        $Uuidapp = Str::uuid()->toString();
        return view('admin.issues.progress', compact(['issues'], ['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data'], ['htissues']));
    }

    public function closed()
    {
        $task = DB::table('task')
        ->select('taskid', 'createtask', 'subject', 'users.name', 'due_date')
        ->join('statustask', 'statustask.statustaskid', '=', 'task.statustask')
        ->join('users', 'users.id', '=', 'task.assignment')
        ->where([['statustask.statustaskid', 3]])
        ->orderBy('taskid', 'DESC')
        ->get();
        $between = null;
        $fromdate = null;
        $todate = null;
        $data = null;
        $Uuidapp = Str::uuid()->toString();
        return view('admin.tasks.closed', compact(['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data'], ['task']));
    }

    public function getReportclosed(Request $request)
    {
        switch ($request->input('action')) {
            case 'search':
                $fromdate = $request->input('fromdate');
                $todate = $request->input('todate');
                if ($request->isMethod('post')) {
                    $between = DB::table('issues_tracker')
                        ->select('issues.Issuesid', 'issues_tracker.TrackName', 'ISSName', 'ISPName', 'issues.Createby', 'Subject', 'issues_logs.create_at')
                        ->join('issues', 'issues.Trackerid', '=', 'issues_tracker.Trackerid')
                        ->join('issues_priority', 'issues.Priorityid', '=', 'issues_priority.Priorityid')
                        ->join('issues_status', 'issues.Statusid', '=', 'issues_status.Statusid')
                        ->join('issues_logs', 'issues_logs.Issuesid', '=', 'issues.Issuesid')
                        ->where([['issues.Statusid', 2], ['issues_logs.Action', 'Closed']])
                        ->whereBetween('issues.Date_In', [$fromdate, $todate])
                        ->orderBy('Issuesid', 'DESC')
                        ->get();
                    $data = DB::table('htissues')
                        ->select('Issuesid', 'ISSName', 'Createby', 'Subject', 'issues.updated_at')
                        ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
                        ->where('htissues.Statusid', 2)
                        ->whereBetween('htissues.Date_In', [$fromdate, $todate])
                        ->orderBy('Issuesid', 'DESC')
                        ->count();
                    $htissues = DB::table('htissues')
                        ->select('htissues.Issuesid', 'issues_status.ISSName', 'htissues.Createby', 'htissues.Subject', 'issues_logs.create_at')
                        ->join('issues_status', 'htissues.Statusid', '=', 'issues_status.Statusid')
                        ->join('issues_logs', 'issues_logs.Issuesid', '=', 'htissues.Issuesid')
                        ->where([['htissues.Statusid', 2], ['issues_logs.Action', 'Closed']])
                        ->orderBy('htissues.Issuesid', 'DESC')
                        ->get();
                } else {
                    $between = null;
                    $data = null;
                }
                $issues = null;
                $Uuidapp = Str::uuid()->toString();
                break;
            case 'export':
                $fromdate = $request->input('fromdate');
                $todate = $request->input('todate');
                $between = null;
                $data = null;
                $issues = null;
                return Excel::download(new FilterExport($fromdate, $todate), 'issues.xlsx');
                break;
        }

        return view('admin.issues.closed', compact(['htissues'], ['between'], ['Uuidapp'], ['fromdate'], ['todate'], ['data']));
    }


    public function create()
    {
        //    echo($Uuidapp);
        $department = Department::all();
        $user = User::all();

        return view('admin.tasks.create', compact(
            ['department'],
            ['user']
        ));
    }


    public function store(Request $request)
    {

        $task = new Task();
        // $issues->Trackerid = $request->input('Trackerid');
        $task->createtask = $request->input('Createby');
        $task->statustask = 1;
        $task->departmentid = $request->input('department');
        $task->assignment = $request->input('assignment');
        $task->subject = $request->input('subject');
        // $issues->Tel = $request->input('Tel');
        // $issues->Comname = $request->input('Comname');
        // $issues->Informer = $request->input('Informer');
        $task->description = $request->input('description');
        $task->assign_date = $request->input('assign_date');
        $task->due_date = DateThai($request->input('due_date'));
        $task->close_date = null;
        $task->created_at = DateThai(now());
        $task->updated_at = DateThai(now());

        if ($request->hasFile('file')) {
            $filename = $request->Image->getClientOriginalName();
            $file = time() . '.' . $filename;
            $task->file = $request->Image->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $task->file = null;
        }

        $task->save();

        return redirect('/tasksnews')->with('status', 'บันทึกงานสำเร็จ');
    }

    public function show($taskid)
    {
        $data = Task::find($taskid);
        $department = Department::all();
        $user = User::all();

        return view('admin.tasks.show', compact(
            ['data'],
            ['user'],
            ['department'],
        ));
    }

    public function GetSubCatAgainstMainCatEdit($id){
        echo json_encode(DB::table('users')->where('departmentid', $id)->get());
    }

    public function edit($taskid)
    {
        $data = Task::find($taskid);
        $user = User::all();
        $department = Department::all();
    
        return view('admin.tasks.edit', compact(
            ['data'],
            ['user'],
            ['department'],
        ));
    }

    public function update(Request $request, $taskid)
    {

        $task = Task::find($taskid);
        // $issues->Trackerid = $request->input('Trackerid');
        $task->createtask = $request->input('Createby');
        // $task->statustask = 1;
        $task->departmentid = $request->input('department');
        $task->assignment = $request->input('assignment');
        $task->subject = $request->input('subject');
        // $issues->Tel = $request->input('Tel');
        // $issues->Comname = $request->input('Comname');
        // $issues->Informer = $request->input('Informer');
        $task->description = $request->input('description');
        $task->assign_date = $request->input('assign_date');
        $task->due_date = DateThai($request->input('due_date'));
        // $task->close_date = null;
        // $task->created_at = DateThai(now());
        $task->updated_at = DateThai(now());

        if ($request->hasFile('file')) {
            $filename = $request->Image->getClientOriginalName();
            $file = time() . '.' . $filename;
            $task->file = $request->Image->storeAs('files', $file, 'public');
            // dd($file);
        } else {
            $task->file = null;
        }


        $task->update();

        return redirect('/tasksnews')->with('status', 'อัพเดทข้อมูลงานสำเร็จ');
    }

    public function fetch(Request $request)
    {
        $select = $request->get('select');
        $TrackName = $request->get('TrackName');
        $SubTrackName = $request->get('SubTrackName');
        $Name = $request->get('Name');
        $dependent = $request->get('dependent');
        // echo $select . "," . $value . "," . $dependent;
        $data = DB::table('issues_tracker')
            ->where($select, $TrackName)
            ->groupBy($dependent)
            ->get();
        $data2 = DB::table('issues_tracker')
            ->where([['TrackName', $TrackName], [$select, $SubTrackName]])
            ->groupBy($dependent)
            ->get();
        $output = '<option value="" disabled="true" selected="true">Select '
            . ucfirst($dependent) . '</option>';

        //echo "DATA:".print_r($data);   

        foreach ($data as $row2) {
            $output = $output . '<option value="' . $row2->$dependent . '" > 
                ' . $row2->$dependent . ' </option>';
        }
        foreach ($data2 as $row3) {
            $output = $output . '<option value="' . $row3->$dependent . '"> 
                ' . $row3->$dependent . ' </option>';
        }
        echo $output;
    }


    public function findid(Request $request)
    {
        $TrackName = $request->get('TrackName');
        $SubTrackName = $request->get('SubTrackName');
        $Name = $request->get('Name');

        //it will get id if its id match with product id

        $p = Issuestracker::select('Trackerid')->where([['TrackName', $TrackName], ['SubTrackName', $SubTrackName], ['Name', $Name]])->first();
        // echo $p;
        // $json = array("Trackerid" => $p->Trackerid);
        // echo print_r($json);
        // return response()->json($json);
        return $p->Trackerid;
    }

    public function findidother(Request $request)
    {
        $TrackName = $request->get('TrackName');
        $SubTrackName = $request->get('SubTrackName');
        $Name = $request->get('Name');

        //it will get id if its id match with product id

        if ($SubTrackName == 'Other') {
            $p2 = Issuestracker::select('Trackerid')->where([['TrackName', $TrackName], ['SubTrackName', 'Other']])->first();
            return $p2->Trackerid;
        }

        // echo $p;
        // $json = array("Trackerid" => $p->Trackerid);
        // echo print_r($json);
        // return response()->json($json);

    }

    public function select2(Request $request)
    {
        $data = [];

        $search = $request->q;
        $data = Department::select("Departmentid", "DmName")
            ->where('DmName', 'LIKE', "%$search%")
            ->get();
        // echo ($data);


        return response()->json($data);
    }
}
