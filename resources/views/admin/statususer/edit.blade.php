@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> แก้ไขข้อมูลประเภทพนักงาน</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('statususer-update/'.$statususer->usertypeid) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ชื่อประเภทพนักงาน</label>
                                <input type="text" name="typename" class="form-control" value="{{$statususer->typename}}">
                                @if($errors->has('typename'))
                                <div class="alert alert-danger">
                                    <li>{{$errors->first('typename')}}</li>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">อัพเดท</button>
                            <a href="/statususer" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection