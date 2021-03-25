@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('เพิ่มข้อมูลพนักงาน') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('register-create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('รหัสพนักงาน(เช่น E0001)') }}</label>

                            <div class="col-md-6">
                                <input id="id" type="text" class="form-control @error('id') is-invalid @enderror" name="id" value="{{ old('id') }}" required autocomplete="id" autofocus>

                                @error('id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('ชื่อ-นามสกุล') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('ชื่อผู้ใช้') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">ประเภทผู้ใช้</label>
                            <div class="col-md-6">
                                <select name="usertypeid" class="form-control">
                                    <option value="1">Admin</option>
                                    <option value="2">SuperUser</option>
                                    <option value="3">User</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="logintype" class="col-md-4 col-form-label text-md-right">ประเภทล็อคอิน</label>
                            <div class="col-md-6">
                                <select name="logintype" class="form-control">
                                    <option value="1">AD</option>
                                    <option value="0">DB</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="departmentid" class="col-md-4 col-form-label text-md-right">แผนก</label>
                            <div class="col-md-6">
                            <p>
                                <select id="departmentid" name="departmentid" class="form-control" require>
                                    @foreach($department as $row3)
                                    <option value="{{$row3->departmentid}}" @if (old("Roomid")==$row3->departmentid) selected @endif>{{$row3->dmname}}</option>
                                    @endforeach
                                </select></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('รหัสผ่าน') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('ยืนยันรหัสผ่าน') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="latitude" class="col-md-4 col-form-label text-md-right">{{ __('ละติจูด') }}</label>

                            <div class="col-md-6">
                                <input id="latitude" type="latitude" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude') }}" required autocomplete="latitude">

                                @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="longitude" class="col-md-4 col-form-label text-md-right">{{ __('ลองจิจูด') }}</label>

                            <div class="col-md-6">
                                <input id="longitude" type="longitude" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude') }}" required autocomplete="longitude">

                                @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                        <label for="Image" class="col-md-4 col-form-label text-md-right">{{ __('รูปโปรไฟล์') }}</label>
                            <div class="col-md-6">
                                <input type="file" id="Image" name="Image">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('ยืนยัน') }}
                                </button>
                                <a href="/role-register" class="btn btn-danger">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection