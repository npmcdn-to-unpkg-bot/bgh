@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open() !!}

            <div class="form-group">
                <label for="username">Username</label>
                {!! Form::text('username',null ,array('class'=>'form-control','placeholder'=>'Choose unique username')) !!}
            </div>

            <div class="form-group">
                <label for="username">Fullname</label>
                {!! Form::text('fullname',null ,array('class'=>'form-control','placeholder'=>'New user fullname')) !!}
            </div>

            <div class="form-group">
                <label for="username">Email</label>
                {!! Form::text('email',null ,array('class'=>'form-control','placeholder'=>'New user email')) !!}
            </div>

            <div class="form-group">
                <label for="username">Password</label>
                {!! Form::password('password',array('class'=>'form-control','placeholder'=>'New user password')) !!}
            </div>


            <div class="form-group">
                {!! Form::submit('Add User',array('class'=>'btn btn-success')) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
