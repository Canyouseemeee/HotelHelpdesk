@extends('layouts.master')

@section('title')
Register Edit
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('แก้ไขข้อมูลพนักงาน') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/role-register-update/{{ $users->id }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <label>รหัสพนักงาน</label>
                                    <input type="text" class="form-control" value="{{$users->id}}" name="id" readonly="true">
                                </div>
                                <div class="form-group">
                                    <label>ชื่อ-นามสกุล</label>
                                    <input id="name" type="text" class="form-control" value="{{$users->name}}" name="name">
                                </div>
                                <div class="form-group">
                                    <label>ประเภทผู้ใช้</label>
                                    <select id="usertypeid" name="usertypeid" class="form-control">
                                        <option value="1" @if ($users->usertypeid === 1)
                                            selected
                                            @endif>Admin</option>
                                        <option value="2" @if ($users->usertypeid === 2)
                                            selected
                                            @endif>SUPERUSER</option>
                                        <option value="3" @if ($users->usertypeid === 3)
                                            selected
                                            @endif>User</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>ประเภทล็อคอิน</label>
                                    <select name="logintype" class="form-control">
                                        <option value="1" @if ($users->logintype === 1)
                                            selected
                                            @endif>AD</option>
                                        <option value="0" @if ($users->logintype === 0)
                                            selected
                                            @endif>DB</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>แผนก</label>
                                    <select id="departmentid" name="departmentid" class="form-control" require>
                                    @foreach($department as $row3)
                                    <option value="{{$row3->departmentid}}"  @if ($row3->departmentid === $users->departmentid)
                                    selected
                                    @endif>{{$row3->dmname}}</option>
                                    @endforeach
                                </select></p>
                                </div>

                                <div class="form-group">
                                    <label>ชื่อผู้ใช้</label>
                                    <input type="text" class="form-control" value="{{$users->username}}" name="username">
                                </div>

                                <div class="form-group">
                                    <label>ละติจูด</label>
                                    <input id="latitude"  class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{$users->latitude}}" required autocomplete="latitude"> 
                                </div>

                                <div class="form-group">
                                    <label>ลองจิจูด</label>
                                    <input id="longitude"  class="form-control @error('latitude') is-invalid @enderror" name="longitude" value="{{$users->longitude}}" required autocomplete="longitude"> 
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Image Profile (ถ้ามีรูปภาพอยู่แล้วไม่ต้องเพิ่มรูป)') }}</label>
                                    <div class="col-md-6">
                                        <input type="file" id="image" name="image">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">อัพเดท</button>
                                <a href="/role-register" class="btn btn-danger">ยกเลิก</a>
                                <!-- <a href="/role-reset/{{$users->id}}" class="btn btn-warning">Reset Password</a> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection