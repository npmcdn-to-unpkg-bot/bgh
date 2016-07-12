@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open() !!}
            <ul>
                <li><h4>New Cache will get auto generated once you clear it.</h4></li>
                <li><h4>Warning: Don't over use this feature..!!</h4></li>
                <li><h4>All logged in users might get logged out</h4></li>
                <li><h4>If something is not working, then clear the cache manually ( Read the docs )</h4></li>
            </ul>
            <hr>
            <div class="form-group">
                {!! Form::label('settings_cache', 'Clear Config Cache') !!}
                {!! Form::checkbox('settings_cache') !!}
            </div>
            <div class="form-group">
                {!! Form::label('template_cache', 'Clear Template Cache') !!}
                {!! Form::checkbox('template_cache') !!}
            </div>
            <div class="form-group">
                {!! Form::label('route_cache', 'Clear Routes cache and optimize it') !!}
                {!! Form::checkbox('route_cache') !!}
            </div>
            <div class="form-group">
                {!! Form::submit('Update',array('class'=>'btn btn-success')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

