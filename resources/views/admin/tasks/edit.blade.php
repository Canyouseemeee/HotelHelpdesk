@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')

<?php
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
?>


<!-- <div class="btn-group btn-group-toggle" data-toggle="buttons"> -->
<button type="button" class="btn btn-outline-warning btn_showIssues active">Issues Create</button>
<button type="button" class="btn btn-outline-primary btn_showComments">Comments</button>
<!-- <button type="button" class="btn btn-outline-danger btn_showAppointments">Appointments</button> -->
<!-- </div> -->

<div class="row subissues">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Issues-Edit</h4>
            </div>
            <div class="card-body">
                @if($errors)
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    <li>{{$error}}</li>
                </div>
                @endforeach
                @endif
                <form action="{{ url('tasks-update/'.$data->taskid) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-row">


                    <div class="form-group col-md-3">
                                    <label>พนักงานที่สร้าง</label>
                                    <input name="Createby" class="form-control" readonly="readonly" value="{{$data->createtask}}" placeholder="{$data->createtask}}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>วันเวลาที่หมอบหมาย</label>
                                    <input name="assign_date" class="form-control" readonly="readonly" value="{{$data->assign_date}}" placeholder="{$data->assign_date}}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="title">แผนก</label>
                                    <select id="department" name="department" class="form-control formselect required">
                                        <option value="0">--- เลือก แผนก ---</option>
                                    @foreach ($department as $key)
                                        <option value="{{ $key->departmentid }}" @if ($key->departmentid === $data->departmentid)
                                    selected
                                    @endif>{{ $key->dmname }}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>หมอบหมายให้</label>
                                    <select id="assignment" name="assignment" class="form-control formselect  create" require>
                                    <option value="">--- เลือก พนักงาน ---</option>
                                    @foreach ($user as $key)
                                        <option value="{{$key->id}}"@if ($key->id === $data->assignment)
                                    selected
                                    @endif>{{ $key->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                        </div>

                    <div class="form-group">
                        <label>หัวเรื่อง</label>
                        <input type="text" name="subject" class="form-control" value="{{$data->subject}}">
                    </div>

                    <div class="form-group">
                        <label>รายละเอียด</label>
                        <textarea type="text" name="description" class="form-control">{{$data->description}}</textarea>
                    </div>

                    <div class="form-group">
                        <label>วันจะให้ที่ส่ง</label>
                        <input type="text" class="form-control datetimepicker" value="{{DateThai($data->due_date)}}" placeholder="{{DateThai($data->due_date)}}" id="due_date" name="due_date" > 
                    </div>

                    <!-- <div>
                        <input type="hidden" name="Image2" value="{{$data->Image}}">
                        <input type="file" name="Image">
                    </div> -->
                    <br>
                    <input type="submit" value="อัพเดท" class="btn btn-primary ">
                    <a href="{{ url('tasks-show/'.$data->taskid) }}" class="btn btn-danger">กลับ</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
<script type="text/javascript">
        $(function () {
            $('.datetimepicker').datetimepicker();
        });
</script>
<script>
    $(document).ready(function() {

        $('.dynamic').change(function() {
            var TrackName = $("#TrackName option:selected").val();
            if (TrackName != '') {
                var select = $(this).attr("id");
                var dependent = $(this).data('dependent');

                var TrackName = $("#TrackName option:selected").val();
                var SubTrackName = $("#SubTrackName option:selected").val();
                var Name = $("#Name option:selected").val();
                console.log(select);
                console.log(TrackName);
                console.log(SubTrackName);
                console.log(Name);
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamiccontroller.fetch') }}",
                    method: "GET",
                    data: {
                        select: select,
                        TrackName: TrackName,
                        SubTrackName: SubTrackName,
                        Name: Name,
                        _token: _token,
                        dependent: dependent
                    },
                    success: function(result) {
                        $('#' + dependent).html(result);
                        $('#SubTrackName').prop('disabled', false);

                    }
                });
            }
            if (TrackName == '') {
                $('#SubTrackName').empty().append('<option>Select SubTrackName</option>');;
                $('#Name').html('<option value="">Select Name</option>');
                $('#tracker_id').val('');
                $('#Name').prop('disabled', false);
            }
            if (SubTrackName != '') {
                $('#Name').html('<option value="">Select Name</option>');
                $('#tracker_id').val('');
                $('#Name').prop('disabled', false);
            }
            if (SubTrackName == '') {
                $('#Name').html('<option value="">Select Name</option>');
                $('#tracker_id').val('');
                $('#Name').prop('disabled', false);
            }
            if (SubTrackName == 'Other') {
                $('#Name').prop('disabled', 'disabled');
            }
            if (SubTrackName != 'Other') {
                $('#Name').prop('disabled', false);
            }
        });



        $(document).on('change', '.Name', function() {
            var SubTrackName = $("#SubTrackName option:selected").val();
            if (SubTrackName != 'Other') {
                var tracker_id = $(this).val();
                var TrackName = $("#TrackName option:selected").val();
                var SubTrackName = $("#SubTrackName option:selected").val();
                var Name = $("#Name option:selected").val();
                var a = $(this).parent();
                // console.log(tracker_id);

                var op = "";
                $.ajax({
                    type: 'get',
                    url: '{!!URL::to("findid")!!}',
                    data: {
                        // 'Name': tracker_id,
                        TrackName: TrackName,
                        SubTrackName: SubTrackName,
                        Name: Name,
                    },
                    dataType: 'json', //return data will be json
                    success: function(data) {
                        // console.log("Trackerid","3");
                        // console.log(len(data));
                        console.log(data);

                        // here price is coloumn name in products table data.coln name
                        $('#Trackerid').val(data);
                        // a.find('.tracker_id').val(data.Name);
                        // console.log(data = JSON.parse(data));



                    },
                    error: function() {

                    }
                });
            }

        });

        $(document).on('change', '.findidother', function() {

            var tracker_id = $(this).val();
            var TrackName = $("#TrackName option:selected").val();
            var SubTrackName = $("#SubTrackName option:selected").val();
            var Name = $("#Name option:selected").val();
            var a = $(this).parent();
            // console.log(tracker_id);

            var op = "";
            $.ajax({
                type: 'get',
                url: '{!!URL::to("findidother")!!}',
                data: {
                    TrackName: TrackName,
                    SubTrackName: SubTrackName,
                    Name: Name,
                },
                dataType: 'json', //return data will be json
                success: function(data) {
                    // console.log("Trackerid","3");
                    // console.log(len(data));
                    console.log(data);

                    // here price is coloumn name in products table data.coln name
                    $('#Trackerid').val(data);
                    // a.find('.tracker_id').val(data.Name);
                    // console.log(data = JSON.parse(data));

                },
                error: function() {

                }
            });


        });

    });
    $(document).ready(function () {
                $('#department').on('change', function () {
                let id = $(this).val();
                $('#assignment').empty();
                $('#assignment').append(`<option value="0" disabled selected>กำลังโหลด...</option>`);
                $.ajax({
                type: 'GET',
                url: '/GetSubCatAgainstMainCatEdit/' + id,
                success: function (response) {
                var response = JSON.parse(response);
                console.log(response);   
                $('#assignment').empty();
                $('#assignment').append(`<option value="0" disabled selected>เลือกพนักงาน</option>`);
                response.forEach(element => {
                    $('#assignment').append(`<option value="${element['id']}">${element['name']}</option>`);
                    });
                }
            });
        });
    });
</script>

<script>
    $('#Typeissuesid').select2({
        placeholder: " Enter Typeissues",
        // minimumInputLength: 1,
        delay: 250,
        allowClear: true,
    });

    $('#Roomid').select2({
        placeholder: " Enter NoRoom",
        // minimumInputLength: 1,
        delay: 250,
        allowClear: true,
    });
</script>

<script>
    function fncConfirm1(commentid) {
        //   var txt;
        $('#resultcomment').empty();
        $('#resultcomment2').empty();

        if (confirm("ท่านต้องการยกเลิกข้อความนี้ใช่หรือไม่ ?")) {
            // txt = "You pressed OK!"+cid;
            $.ajax({
                type: "POST",
                data: {
                    commentid: commentid
                },
                url: "/api/commentliststatus",
                success: function(response) {
                    console.log(response);
                    // $('#SubmitUnsend').attr('disabled', 'disabled');
                    $("#resultcomment2").html('<div class="alert alert-danger" role="alert" id="result">Comments Unsend Sucess</div>');
                    $('#cardcomment').empty();
                    $('#countcomment').empty();
                    var temp = $('#Ctemp').val();
                    $.ajax({
                        type: "POST",
                        data: {
                            temp: temp
                        },
                        url: "/api/commentlist",
                        success: function(response) {
                            $('#savecomment').removeAttr('disabled');
                            $('#CComment').removeAttr('readonly').val("");
                            // $("#resultcomment").empty();
                            $('#image').val("");
                            var len = response.length;
                            if (len > 0) {
                                var irow = response.length;
                                var i = 0;
                                var rown = 1;
                                var html2 = '<br><h4 class="card-title">';
                                html2 += 'Comments(' + response.length + ')';
                                html2 += '</h4>';
                                for (i = 0; i < irow; i++) {
                                    var html = '<div class="card-header">';
                                    html += '<div class="user-block">';
                                    if (response[i].image != null) {
                                        // html += '<td><img src="http://10.57.34.148:8000/storage/' + response[i].Image + '" alt="image" width="80" height="80"></td>';
                                        html += '<img class="img-circle" src="/storage/' + response[i].image + '" alt="Image" width="50" height="50"> &nbsp;'
                                    }
                                    html += '<span class="username">' + response[i].Createby + ' : </span>'
                                    html += '<span class="description">' + response[i].created_at + ' </span>'
                                    if (response[i].Type == 1) {
                                        html += '<span class="description">: App </span>';
                                    }
                                    if (response[i].Type == 0) {
                                        html += '<span class="description">: Web </span>';
                                    }
                                    if (response[i].Status === 1) {
                                        html += '<span class="description" style="color: green;">#Active</span>';
                                    }
                                    if (response[i].Status === 0) {
                                        html += '<span class="description" style="color: red;">#UnActive</span>';
                                    }

                                    html += '</div>'
                                    html += '</div>'
                                    html += '<div class="card-body">'
                                    if (response[i].Status === 1) {
                                        html += '<button id="Unsend1" type="button" OnClick="JavaScript:fncConfirm1(' + response[i].Commentid + ');"  class="btn btn-default btn-sm float-right"><i class="fas fa-comment-slash"></i></i> Unsend</button>'
                                    }
                                    if (response[i].Image != null) {
                                        html += '<img class="img-fluid pad center" src="/storage/' + response[i].Image + '" style="align-items: center;" width="555" height="550" alt="Photo">'
                                    } else {
                                        html += '<p style="padding-top: 20px;">ไม่มีรูปภาพ</p>'
                                    }
                                    html += '<p style="padding-top: 20px;">' + response[i].Comment + '</p>'
                                    html += '<span class="float-right text-muted">updated ' + response[i].updated_at + ' By ' + response[i].Updateby + '</span>'
                                    html += '</div>'
                                    html += '<div class="card-footer">'
                                    html += '<bt>'
                                    html += '</div>'

                                    $('#cardcomment').append(html);
                                }
                                $('#countcomment').append(html2);
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },
                error: function(error) {
                    console.log(error);
                    // $('#SubmitUnsend').attr('disabled', 'disabled');
                    // alert("Data Saved");
                    $("#resultcomment2").html('<div class="alert alert-danger" role="alert" id="result">Comments Unsend Sucess</div>');
                }
            });
        } else {
            // txt = "You pressed Cancel!";
        }
        //   console.log(txt);
        // document.getElementById("demo").innerHTML = txt;
    }
</script>

<script>
    $('.panelsub_all').hide();


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

    $(document).ready(function() {

        $('#addform').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/issues-appointment-add",
                data: $('#addform').serialize(),
                success: function(response) {
                    console.log(response);
                    // alert("Data Saved");
                    $('#savemodal').attr('disabled', 'disabled');
                    $('#AppointDate').attr('readonly', 'readonly');
                    $('#Comment').attr('readonly', 'readonly');
                    $('#Status').attr('disabled', 'disabled');
                    $("#result").html('<div class="alert alert-success" role="alert" id="result">Appointment Save Success</div>');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $('#appointmentclosed').click(function() {

            $('#datatableappointbody').empty();
            var temp = $('#tempappoint').val();
            $.ajax({
                type: "POST",
                data: {
                    temp: temp
                },
                url: "/api/appointmentlist",
                success: function(response) {
                    $('#savemodal').removeAttr('disabled').val("");
                    $('#AppointDate').removeAttr('readonly').val("");
                    $('#Comment').removeAttr('readonly').val("");
                    $('#Status').removeAttr('disabled');
                    $("#result").empty();
                    $("#issueseditModal").empty();
                    var len = response.length;
                    if (len > 0) {
                        var irow = response.length;
                        var i = 0;
                        var rown = 1;
                        for (i = 0; i < irow; i++) {
                            var html = "<tr>";
                            html += '<td>' + response[i].Date + '</td>';
                            html += '<td><div class="w-11p" style="height: 30px; overflow: hidden;">' + response[i].Comment + '</div></td>';
                            if (response[i].Status == 1) {
                                html += '<td>Active</td>';
                            }
                            if (response[i].Status == 2) {
                                html += '<td>Change</td>';
                            }
                            if (response[i].Status == 3) {
                                html += '<td>Disable</td>';
                            }
                            html += '<td>' + response[i].Createby + '</td>';
                            html += '<td>' + response[i].Updateby + '</td>';
                            html += '<td>' + response[i].created_at + '</td>';
                            html += '<td>' + response[i].updated_at + '</td>';
                            html += '</tr>';
                            $('#datatableappointbody').append(html);
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });

    $(document).ready(function() {

        $('#savecomment').on('click', function(e) {
            e.preventDefault();
            var form = $('#addformcomment')[0];
            // alert("Data Saved");

            var data = new FormData(form);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "/issues-comments-add",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    console.log(response);
                    // alert("Data Saved");
                    // $('#savecomment').attr('disabled', 'disabled');
                    // $('#CComment').attr('readonly', 'readonly');
                    $("#resultcomment").html('<div class="alert alert-success" role="alert" id="result">Comments Save Success</div>');
                    $('#cardcomment').empty();
                    $('#countcomment').empty();
                    var temp = $('#Ctemp').val();
                    $.ajax({
                        type: "POST",
                        data: {
                            temp: temp
                        },
                        url: "/api/commentlist",
                        success: function(response) {
                            $('#savecomment').removeAttr('disabled');
                            $('#CComment').removeAttr('readonly').val("");
                            // $("#resultcomment").empty();
                            $('#image').val("");
                            var len = response.length;
                            if (len > 0) {
                                var irow = response.length;
                                var i = 0;
                                var rown = 1;
                                var html2 = '<br><h4 class="card-title">';
                                html2 += 'Comments (' + response.length + ')';
                                html2 += '</h4>';
                                for (i = 0; i < irow; i++) {
                                    var html = '<div class="card-header">';
                                    html += '<div class="user-block">';
                                    if (response[i].image != null) {
                                        // html += '<td><img src="http://10.57.34.148:8000/storage/' + response[i].Image + '" alt="image" width="80" height="80"></td>';
                                        html += '<img class="img-circle" src="/storage/' + response[i].image + '" alt="Image" width="50" height="50"> &nbsp;';
                                    }
                                    html += '<span class="username">' + response[i].Createby + ' : </span>';
                                    html += '<span class="description">' + response[i].created_at + ' </span>';
                                    if (response[i].Type == 1) {
                                        html += '<span class="description">: App </span>';
                                    }
                                    if (response[i].Type == 0) {
                                        html += '<span class="description">: Web </span>';
                                    }
                                    if (response[i].Status === 1) {
                                        html += '<span class="description" style="color: green;">#Active</span>';
                                    }
                                    if (response[i].Status === 0) {
                                        html += '<span class="description" style="color: red;">#UnActive</span>';
                                    }
                                    html += '</div>';
                                    html += '</div>';
                                    html += '<div class="card-body">';
                                    if (response[i].Status === 1) {
                                        html += '<button id="Unsend1" type="button" OnClick="JavaScript:fncConfirm1(' + response[i].Commentid + ');"  class="btn btn-default btn-sm float-right"><i class="fas fa-comment-slash"></i></i> Unsend</button>'
                                    }
                                    if (response[i].Image != null) {
                                        html += '<img class="img-fluid pad center" src="/storage/' + response[i].Image + '" style="align-items: center;" width="555" height="550" alt="Photo">';
                                    } else {
                                        html += '<p style="padding-top: 20px;">ไม่มีรูปภาพ</p>';
                                    }
                                    html += '<p style="padding-top: 20px;">' + response[i].Comment + '</p>';
                                    html += '<span class="float-right text-muted">updated ' + response[i].updated_at + ' By ' + response[i].Updateby + '</span>';
                                    html += '</div>';
                                    html += '<div class="card-footer">';
                                    html += '<bt>';
                                    html += '</div>';

                                    $('#cardcomment').append(html);
                                }
                                $('#countcomment').append(html2);
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },
                error: function(error) {
                    console.log(error);
                    // alert("Data error");

                }
            });
        });
    });
</script>
@endsection