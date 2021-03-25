@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">เพิ่มข้อมูลประเภทงาน</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('statustask-store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ชื่อประเภทงาน</label>
                                <input type="text" name="statustaskname" class="form-control" placeholder="กรอกข้อมูลชื่อประเภทงาน">
                                @if($errors->has('statustaskname'))
                                <div class="alert alert-danger">
                                    <li>{{$errors->first('statustaskname')}}</li>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="/statustask" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection