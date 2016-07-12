@extends('master/index')
@section('content')
    <h1 class="content-heading">{{ t('Password Reset') }}</h1>

    {!! Form::open()  !!}
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group">
        <label for="email">{{ t('Email') }}<small>*</small></label>
        {!! Form::text('email',null,['class'=>'form-control input-lg','id'=>'email','placeholder'=>'Your Email','required'=>'required'])  !!}
    </div>
    <div class="form-group">
        <label for="password">{{ t('Password') }}<small>*</small></label>
        {!! Form::password('password',['class'=>'form-control input-lg','id'=>'email','placeholder'=>'Your Password','required'=>'required'])  !!}
    </div>
    <div class="form-group">
        <label for="password_confirmation">{{ t('Confirm Password') }}<small>*</small></label>
        {!! Form::password('password_confirmation',['class'=>'form-control input-lg','id'=>'email','placeholder'=>'Your Password','required'=>'required'])  !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Reset Password',['class'=>'btn btn-success'])  !!}
    </div>
    {!! Form::close() !!}
@endsection