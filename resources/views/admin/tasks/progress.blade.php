@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')
<?php

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear $strHour:$strMinute น.";
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header py-3 ">
                <h4 class="card-title"> งานกำลังดำเนินการ
                    <a href="{{ url('/tasks-create') }}" class="btn btn-primary float-right">เพิ่มข้อมูลงาน</a>
                </h4>
            </div>
            <style>
                .w-10p {
                    width: 10% !important;
                }

                .w-11p {
                    width: 300px;
                    
                }
            </style>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table " id="datatable" width="100%" cellspacing="0">
                        <thead class="text-primary">
                            <th class="w-10p">ไอดี</th>
                            <!-- <th class="w-10p">Tracker</th> -->
                            <th class="w-10p">คนที่สร้าง</th>
                            <!-- <th class="w-10p">Priority</th> -->
                            <th class="w-10p">หัวเรื่อง</th>
                            <th class="w-10p">หมอบหมายให้</th>
                            <th class="w-10p">ส่งวันที่</th>
                            <th class="w-10p">ดู</th>
                        </thead>
                        @if (!is_null($task))
                        <tbody>
                            @foreach($task as $row)
                            <tr>
                                <td>{{$row->taskid}}</td>
                                <td>{{$row->createtask}}</td>
                                <td>
                                    <div class="w-11p" style="height: 30px; overflow: hidden;">
                                        <a href="{{ url('tasks-show/'.$row->taskid) }}">{{$row->subject}}</a>
                                    </div>
                                </td>
                                <td>{{$row->name}}</td>
                                <td>{{DateThai($row->due_date)}}</td>
                                <td>
                                    <a href="{{ url('tasks-show/'.$row->taskid) }}" class="btn btn-success">ดู</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @if (!is_null($between))
                            @foreach ($between as $betweens)
                            <tr>
                                <th scope="row">{{$betweens->taskid}}</th>
                                <td style="text-align:center">{{$betweens->createtask}}</td>
                                <td>
                                    <div class="w-11p" style="height: 30px; overflow: hidden;">
                                        <a href="{{ url('tasks-show/'.$betweens->Issuesid) }}">{{$betweens->subject}}</a>
                                    </div>
                                </td>
                                <td style="text-align:center">{{$betweens->name}}</td>
                                <td style="text-align:center">{{DateThai($betweens->due_date)}}</td>
                                <td>
                                    <a href="{{ url('task-show/'.$betweens->taskid) }}" class="btn btn-success">ดู</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'print']
        });
    });

    $('#datatable').on('click', '.deletebtn', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children('td').map(function() {
            return $(this).text();
        }).get();

        // console.log(data);

        $('#delete_department_id').val(data[0]);

        $('#delete_modal_Form').attr('action', '/department-delete/' + data[0]);

        $('#deletemodalpop').modal('show');
    });
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        var from = $('#fromdate').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true
            }).on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
            }),
            to = $('#todate').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true
            }).on("change", function() {
                from.datepicker("option", "maxDate", getDate(this));
            });

        function getDate(element) {
            var date;
            var dateFormat = "yy-mm-dd";
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }
            return date;
        }
    });
</script>
@endsection