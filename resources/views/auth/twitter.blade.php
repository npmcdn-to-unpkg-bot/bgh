@extends('master/index')
@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ trans(Session::get('reason')) }}</strong>
        </div>
    @endif
    <h1 class="content-heading">{{ t('Registration') }}</h1>
    @if(!session('site_user') && !session('site_user') && !session('user_email'))
    {!! Form::open() !!}
    <div class="form-group">
        <label for="email">{{ t('Your Email') }}<small>*</small></label>
        {!! Form::text('email',null,['class'=>'form-control input-lg','id'=>'username','placeholder'=> t('Your Email'),'required'=>'required']) !!}
    </div>
    {!! Form::submit('Create New Account',['class'=>'btn btn-success btn-lg'])  !!}
    {!! Form::close() !!}
    @endif

    @if(session('site_user'))
        <div class="col-md-2">
            <div class="row">
                <img src="{{ Resize::avatar(session('site_user'),'mainAvatar') }}" class="img img-responsive thumbnail">
                <h3 style="margin-top:-10px">
                    <a href="{{ route('user', [session('site_user')->username]) }}" target="_blank">{{ session('site_user')->fullname }}</a>
                </h3>
            </div>
        </div>
        <div class="col-md-9">
            <strong>{{ t('We have found a account with this email, enter current password to link it.') }}</strong>
            {!! Form::open() !!}
            <div class="form-group">
                <label for="password">{{ t('Password') }}<small>*</small></label>
                {!! Form::password('password',array('class'=>'form-control input-lg','id'=>'password','placeholder'=>'Enter Password','autocomplete'=>'off','required'=>'required'))  !!}
            </div>
            {!! Form::submit('Create New Account',['class'=>'btn btn-success btn-lg'])  !!}
            {!! Form::close() !!}
        </div>
    @endif

    @if(Session::has('user_email'))
    {!! Form::open() !!}
    <div class="form-group">
        <label for="username">{{ t('Select Username') }}<small>*</small></label>
        {!! Form::text('username',session('twitter_register')->nickname,['class'=>'form-control input-lg','id'=>'username','placeholder'=>'Select Username','required'=>'required']) !!}
    </div>

    <div class="form-group">
        <label for="password">{{ t('Password') }}<small>*</small></label>
        {!! Form::password('password',array('class'=>'form-control input-lg','id'=>'password','placeholder'=>'Enter Password','autocomplete'=>'off','required'=>'required'))  !!}
    </div>
    <div class="form-group">
        <label for="password_confirmation">{{ t('Retype Password') }}<small>*</small></label>
        {!! Form::password('password_confirmation',array('class'=>'form-control input-lg','id'=>'password_confirmation','placeholder'=>'Confirm Password','autocomplete'=>'off','required'=>'required'))  !!}
    </div>
    <p><small>By clicking on the "create account" you accept the following <a href="{{ route('tos') }}">terms and conditions</a> and our <a href="{{ route('privacy') }}">privacy policy</a></small></p>
    {!! Form::submit('Create New Account',array('class'=>'btn btn-success btn-lg'))  !!}
    {!! Form::close()  !!}
    @endif
@endsection