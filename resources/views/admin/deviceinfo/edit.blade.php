@extends('layouts.master')

@section('title')
Web Test
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Device-Edit</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('device-update/'.$deviceinfo->deviceinfoid) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> Device</label>
                                <input type="text" name="deviceid" class="form-control" value="{{$deviceinfo->deviceid}}">
                                @if($errors->has('deviceid'))
                                <div class="alert alert-danger">
                                    <li>{{$errors->first('deviceid')}}</li>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">SAVE</button>
                            <a href="/device" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection