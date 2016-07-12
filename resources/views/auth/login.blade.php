@extends('master/index')

@section('content')
    <h1 class="content-heading">{{ t('Login') }}</h1>

    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('username', t('Username or Email address')) !!}
        {!! Form::text('username',null,array('class'=>'form-control input-lg','id'=>'username','placeholder'=> t('Username or Email address'), 'required')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('password', t('Password')) !!}
        {!! Form::password('password',['class'=>'form-control input-lg','id'=>'password','placeholder'=>t('Password'),'autocomplete'=>'off', 'required']) !!}
    </div>
    <div class="form-group">
        <label>
            {{ t('Remember Me') }} {!! Form::checkbox('remember-me', 'value') !!}
        </label>
        &nbsp;&middot;&nbsp; <a href="{{ route('password.reminder') }}">{{ t('Forgot your password?') }}</a>
    </div>
    @if(Session::get('force_captcha'))
        <div class="form-group">
            <label for="recaptcha">{{ t('Type these words') }}
                <small>*</small>
            </label>
            {!! Recaptcha::render() !!}
        </div>
    @endif
    <div class="form-inline">
    <div class="form-group">
        <div class="form-group">{!! Form::submit(t('Login'),['class'=>'btn btn-success btn-lg']) !!}</div>
        <div class="form-group"><strong>Or</strong></div>
        <div class="form-group">&nbsp;<a href="{{ url('auth/facebook') }}"><img src="{{ asset('static/img/f.png') }}"></a></div>
        <div class="form-group">&nbsp;<a href="{{ url('auth/google') }}"><img src="{{ asset('static/img/g.png') }}"></a></div>
        <div class="form-group">&nbsp;<a href="{{ url('auth/twitter') }}"><img src="{{ asset('static/img/t.png') }}"></a></div>
    </div>
    </div>
    {!! Form::close() !!}
@endsection