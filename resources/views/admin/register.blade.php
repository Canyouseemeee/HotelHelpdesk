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
            <style type="text/css">
                body {
                    counter-reset: mycount;           /* Set the counter to 0 */
                }
                .mycount:before{
                    counter-increment: mycount;      /* Increment the counter */
                    content: counter(mycount) ". "; /* Display the counter */
                }
                .test {text-align: center;}
            </style>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead class="text-primary">
                            <th class="test">ลำดับ</th>
                            <th class="test">รูปภาพ</th>
                            <th class="test">รหัสพนักงาน</th>
                            <th class="test">ชื่อ-นามสกุล</th>
                            <th class="test">แผนก</th>
                            <th class="test">ชื่อผู้ใช้</th>
                            <th class="test">ประเภทล็อคอิน</th>
                            <th class="test">ประเภทผู้ใช้</th>
                            <th class="test">การกระทำ</th>
                            <th class="test">การกระทำ</th>
                            <th class="test">สถานะ</th>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <input type="hidden" class="catedelete_val" value="{{$row->id}}">
                                <!-- <ol start="50"> -->
                                <td class="test"><span class="mycount"></span></td>
                                @if($row->image === null)
                                <td>ไม่มีรูปภาพ</td>
                                @else
                                <td class="test"><img src="{{ url('storage/'.$row->image) }}" alt="image" width="80" height="80"></td>
                                @endif
                                <td class="test catedelete_val">{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->dmname}}</td>
                          
                                <td>{{$row->username}}</td>
                                @if($row->logintype === 1)
                                <td class="test">AD</td>
                                @elseif($row->logintype === 0)
                                <td class="test">DB</td>
                                @endif
                                @if($row->usertypeid === 1)
                                <td class="test">ADMIN</td>
                                @elseif($row->usertypeid === 2)
                                <td class="test">SUPERUSER</td>
                                @elseif($row->usertypeid === 3)
                                <td class="test">USER</td>
                                @endif
                                <td class="test">
                                    <a href="/role-edit/{{$row->id}}" class="btn btn-success test">แก้ไข</a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-circle cagetorydeletebtn" ><i class="fas fa-trash"></i></button>
                                </td>
                                <td><input type="checkbox" class="toggle-class" data-id="{{$row->id}}" data-toggle="toggle" data-on="เปืด" data-off="ปิด" {{$row->active==true ? 'checked':''}}></td>
                                <td>
                                <!-- </ol> -->
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

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $('#datatable').DataTable();

        $('.cagetorydeletebtn').click(function(e) {
            e.preventDefault();
            var delete_id = $(this).closest('tr')
                .find('.catedelete_val').val();

            swal({
                    title: "ท่านต้องการลบข้อมูลพนักงงานใช่หรือไม่",
                    // text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var data = {
                            "_token": $('input[name="_token"]').val(),
                            "id": delete_id,
                        };

                        $.ajax({
                            type: "DELETE",
                            url: '/role-delete/' + delete_id,
                            data: data,
                            success: function(response) {
                                swal(response.status, {
                                    icon: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
        });
    });
</script>
@endsection