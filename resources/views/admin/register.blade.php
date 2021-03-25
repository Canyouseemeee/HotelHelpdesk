@extends('layouts.master')

@section('title')
Register
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">จัดการข้อมูลพนักงาน <a href="{{ url('create-user') }}" class="btn btn-primary float-right">เพิ่มข้อมูลพนักงาน</a></h4>
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead class="text-primary">
                            <th>รหัสพนักงาน</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>แผนก</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>ประเภทล็อคอิน</th>
                            <th>ประเภทผู้ใช้</th>
                            <th>แก้ไข</th>
                            <th>สถานะ</th>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                @if($row->image === null)
                                <td>ไม่มีรูปภาพ</td>
                                @else
                                <td><img src="{{ url('storage/'.$row->image) }}" alt="image" width="80" height="80"></td>
                                @endif
                                <td>{{$row->name}}</td>
                                <td>{{$row->dmname}}</td>
                          
                                <td>{{$row->username}}</td>
                                @if($row->logintype === 1)
                                <td>AD</td>
                                @elseif($row->logintype === 0)
                                <td>DB</td>
                                @endif
                                @if($row->usertypeid === 1)
                                <td>ADMIN</td>
                                @elseif($row->usertypeid === 2)
                                <td>SUPERUSER</td>
                                @elseif($row->usertypeid === 3)
                                <td>USER</td>
                                @endif
                                <td>
                                    <a href="/role-edit/{{$row->id}}" class="btn btn-success">แก้ไข</a>
                                </td>
                                <td><input type="checkbox" class="toggle-class" data-id="{{$row->id}}" data-toggle="toggle" data-on="เปืด" data-off="ปิด" {{$row->active==true ? 'checked':''}}></td>
                                <td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>

<script>
    $(function() {
        $('#toggle-two').bootstrapToggle({
            on: 'Enabled',
            off: 'Disabled',
            onstyle: 'primary'
        });
    });

    $('.toggle-class').on('change', function() {
        var active = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        // alert(id);
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '{{route("change_active")}}',
            data: {
                'active': active,
                'id': id
            },
            success: function(data) {
                $('.message').html('<p class="alert alert-danger">' + data.success + '</p>');
            }
        });
    });
</script>
@endsection