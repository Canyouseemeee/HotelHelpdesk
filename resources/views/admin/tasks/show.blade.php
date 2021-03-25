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

function DateThai2($strDate)
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

function formatdate($strDate)
{
    $dateinterval = $strDate;
    return $dateinterval->format('%D day %H:%I:%S');
}

function DateTime($strDate)
{

    $newDate = date('Y-m-d\TH:i', strtotime($strDate));
    return "$newDate";
}

?>

<!-- The Modal -->
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>


<!-- <button type="button" class="btn btn-outline-warning btn_showIssues active">Issues Create</button> -->
<!-- <button type="button" class="btn btn-outline-primary btn_showComments">Comments</button> -->
<!-- <button type="button" class="btn btn-outline-danger btn_showAppointments">Appointments</button> -->


<form action="{{ url('tasks-show/'.$data->taskid) }}" method="PUT">
    {{ csrf_field() }}
    <div class="row subissues">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> รายละเอียดงาน</h4>
                </div>
                <style>
                    .SandD {
                        width: 900px;
                        word-wrap: break-word;
                    }

                    #myImg {
                        border-radius: 5px;
                        cursor: pointer;
                        transition: 0.3s;
                    }

                    #myImg:hover {
                        opacity: 0.7;
                    }

                    /* The Modal (background) */
                    .modal {
                        display: none;
                        /* Hidden by default */
                        position: fixed;
                        /* Stay in place */
                        z-index: 1;
                        /* Sit on top */
                        padding-top: 100px;
                        /* Location of the box */
                        left: 0;
                        top: 0;
                        width: 100%;
                        /* Full width */
                        height: 100%;
                        /* Full height */
                        overflow: auto;
                        /* Enable scroll if needed */
                        background-color: rgb(0, 0, 0);
                        /* Fallback color */
                        background-color: rgba(0, 0, 0, 0.9);
                        /* Black w/ opacity */
                    }

                    /* Modal Content (Image) */
                    .modal-content {
                        margin: auto;
                        display: block;
                        width: 80%;
                        max-width: 700px;
                    }

                    /* Caption of Modal Image (Image Text) - Same Width as the Image */
                    #caption {
                        margin: auto;
                        display: block;
                        width: 80%;
                        max-width: 700px;
                        text-align: center;
                        color: #ccc;
                        padding: 10px 0;
                        height: 150px;
                    }

                    /* Add Animation - Zoom in the Modal */
                    .modal-content,
                    #caption {
                        animation-name: zoom;
                        animation-duration: 0.6s;
                    }

                    @keyframes zoom {
                        from {
                            transform: scale(0)
                        }

                        to {
                            transform: scale(1)
                        }
                    }

                    /* The Close Button */
                    .close {
                        position: absolute;
                        top: 15px;
                        right: 35px;
                        color: #f1f1f1;
                        font-size: 40px;
                        font-weight: bold;
                        transition: 0.3s;
                    }

                    .close:hover,
                    .close:focus {
                        color: #bbb;
                        text-decoration: none;
                        cursor: pointer;
                    }

                    /* 100% Image Width on Smaller Screens */
                    @media only screen and (max-width: 700px) {
                        .modal-content {
                            width: 100%;
                        }
                    }
                </style>
                <div class="container">
                    <div class="card-body row">
                        <div class="" style="font-size:20px">
                            <div class="form-row ">

                                <div class="form-group col-md-3">
                                <b><label>พนักงานที่สร้าง: </label></b>
                                    <label>{{$data->createtask}}</label>
                                </div>

                                <div class="form-group col-md-3">
                                <b><label>วันเวลาที่หมอบหมาย: </label></b>
                                    <label>{{$data->assign_date}}</label>
                                    <!-- <input name="assign_date" class="form-control" readonly="readonly" value="{{$data->assign_date}}" placeholder="{$data->assign_date}}"> -->
                                </div>

                                <div class="form-group col-md-3">
                                <b><label for="title">แผนก: </label></b>
                                    @foreach ($department as $key)
                                    @if ($key->departmentid === $data->departmentid)
                                    <label >{{$key->dmname}}</label>
                                    @endif
                                    @endforeach
                                </div>

                                <div class="form-group col-md-3">
                                <b><label>หมอบหมายให้: </label></b>
                                    @foreach ($user as $key)
                                    @if ($key->id === $data->assignment)
                                    <label >{{$key->name}}</label>
                                    @endif
                                    @endforeach
                                </div>

                        </div>

                            <b><label>หัวเรื่อง : </label></b>
                            <div class="form-group col-md-10">
                                <label class="SandD">{{$data->subject}}</label>
                            </div>


                            <b><label>รายละเอียด : </label></b>
                            <div class="form-group col-md-10">
                                <label class="SandD">{{$data->description}}</label>
                            </div>

                            <div class="form-row ">
                               
                                <div class="form-group col-md-4">
                                    <b> <label>วันที่ส่งงาน : </label></b>
                                    <label>{{DateThai($data->due_date)}}</label>
                                </div>
    

                                <div class="form-group col-md-4">
                                    <b> <label>วันที่สร้าง : </label></b>
                                    <label>{{DateThai($data->created_at)}}</label>
                                </div>

                                <div class="form-group col-md-4">
                                    <b> <label>วันที่อัพเดท : </label></b>
                                    <label>{{DateThai2($data->updated_at)}}</label>
                                </div>

                                
                            </div>

                            <!-- @if ($data->file != null)
                            <a href="{{ route('dowloadfile', $data->taskid) }}" class="btn btn-warning">ดาวน์โหลด</a>
                            @endif -->

                            <!-- <div class="form-group">
                                <b><label>ไฟล์งาน : </label></b>
                                <img id="myImg" src="{{ url('storage/'.$data->file) }}" alt="Image" style="width:100%;max-width:300px">
                            </div> -->

                        </div>
                        <a href="{{ url('tasks-edit/'.$data->taskid) }}" class="btn btn-primary">แก้ไข</a>
                        &nbsp;&nbsp;
                        <a href="/tasks" class="btn btn-danger">กลับ</a>
                        &nbsp;&nbsp;
                        <!-- <a href="{{ url('pdf/'.$data->Issuesid)}}" class="btn btn-warning"> PDF</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
    $('.panelsub_all').hide();

    $('.btn_showAppointments').click(function(e) {
        e.preventDefault();
        $('.subappoint').show();
        $('.subissues').hide();
        $('.subcomment').hide();
        $(this).addClass('active');
        $('.btn_showIssues').removeClass('active')
        $('.btn_showComments').removeClass('active')
    });

    $('.btn_showIssues').click(function(e) {
        e.preventDefault();
        $('.subappoint').hide();
        $('.subissues').show();
        $('.subcomment').hide();
        $(this).addClass('active');
        $('.btn_showComments').removeClass('active')
        $('.btn_showAppointments').removeClass('active')
    });

    $('.btn_showComments').click(function(e) {
        e.preventDefault();
        $('.subappoint').hide();
        $('.subissues').hide();
        $('.subcomment').show();
        $(this).addClass('active');
        $('.btn_showAppointments').removeClass('active')
        $('.btn_showIssues').removeClass('active')
    });
</script>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    img.onclick = function() {
        document.getElementById("myModal").style.display = 'block';
        modalImg.src = this.src;
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>

@endsection