@extends('master/index')
@section('content')
    <h1 class="content-heading">{{ t('Password Reset') }}</h1>

    {!! Form::open()  !!}
    <div class="form-group">
        <label for="email">Email<small>*</small></label>
        {!! Form::text('email',null,['class'=>'form-control input-lg','id'=>'email','placeholder'=>'Your Email','required'=>'required'])  !!}
    </div>
    <div class="form-group">
        <label for="recaptcha">Type these words<small>*</small></label>
        {!! Recaptcha::render() !!}
    </div>
    <div class="form-group">
         {!! Form::submit('Reset Password',['class'=>'btn btn-success'])  !!}
     </div>
     {!! Form::close() !!}
@endsection