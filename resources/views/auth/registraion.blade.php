@extends('master/index')

@section('content')
    @if (Session::has('error'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ trans(Session::get('reason')) }}</strong>
        </div>
    @endif
    <h1 class="content-heading">{{ t('Registration') }}</h1>
    <h3 class="content-heading">
        <div class="form-inline">
            <div class="form-group">
                <div class="form-group">&nbsp;<a href="{{ url('auth/facebook') }}"><img src="{{ asset('static/img/f.png') }}"></a></div>
                <div class="form-group">&nbsp;<a href="{{ url('auth/google') }}"><img src="{{ asset('static/img/g.png') }}"></a></div>
                <div class="form-group">&nbsp;<a href="{{ url('auth/twitter') }}"><img src="{{ asset('static/img/t.png') }}"></a></div>
            </div>
         </div>
    </h3>
    {!! Form::open() !!}

    <div class="form-group">
        <label for="username">{{ t('Select Username') }}
            <small>*</small>
        </label>
        {!! Form::text('username',null,['class'=>'form-control input-lg','id'=>'username','placeholder'=>t('Select Username'),'required']) !!}
    </div>

    <div class="form-group">
        <label for="email">{{ t('Your Email') }}
            <small>*</small>
        </label>
        {!! Form::text('email',null,['class'=>'form-control input-lg','type'=>'email','id'=>'email','placeholder'=>t('Your Email'),'required']) !!}
    </div>
    <div class="form-group">
        <label for="fullname">{{ t('Your Full Name') }}
            <small>*</small>
        </label>
        {!! Form::text('fullname',null,['class'=>'form-control input-lg','id'=>'fullname','placeholder'=>t('Your Full Name'),'required'=>'required']) !!}
    </div>

    <div class="form-group">
        <label for="gender">{{ t('Gender') }}
            <small>*</small>
        </label>
        {!! Form::select('gender', ['male' => t('Male'), 'female' => t('Female')], 'male',['class'=>'form-control input-lg','required'=>'required']) !!}
    </div>


    <div class="form-group">
        <label for="password">{{ t('Password') }}
            <small>*</small>
        </label>
        {!! Form::password('password',['class'=>'form-control input-lg','placeholder'=>t('Enter Password'),'autocomplete'=>'off','required'=>'required']) !!}
    </div>

    <div class="form-group">
        <label for="password_confirmation">{{ t('Retype Password') }}
            <small>*</small>
        </label>
        {!! Form::password('password_confirmation',['class'=>'form-control input-lg','placeholder'=>'Confirm Password','autocomplete'=>'off','required'=>'required']) !!}
    </div>

    <div class="form-group">
        <label for="recaptcha">{{ t('Type these words') }}
            <small>*</small>
        </label>
        {!! Recaptcha::render() !!}
    </div>
    <p>
        <small>{{ t('By clicking on the "create account" you accept the following') }} <a href="{{ route('tos') }}">{{ t('terms and conditions') }}</a> {{ t('and our') }} <a href="{{ route('privacy') }}">{{ t('privacy policy') }}</a></small>
    </p>
    <div class="form-group">
        {!! Form::submit(t('Create New Account'),['class'=>'btn btn-success btn-lg']) !!}
    </div>
    {!! Form::close() !!}
@endsection