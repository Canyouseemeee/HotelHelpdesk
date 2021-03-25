@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> แก้ไขข้อมูลแผนก</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('department-update/'.$department->departmentid) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ชื่อแผนก</label>
                                <input type="text" name="dmname" class="form-control" value="{{$department->dmname}}">
                                @if($errors->has('dmname'))
                                <div class="alert alert-danger">
                                    <li>{{$errors->first('dmname')}}</li>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                            <a href="/department" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection