@extends('master/index')
@section('content')
    <h1 class="content-heading">{{ t('Report') }}</h1>
    {!! Form::open()  !!}
    <div class="form-group">
        <label for="report">{{ t('Reason For Reporting') }}</label>
        {!! Form::textarea('report',null,['class'=>'form-control','id'=>'username','placeholder'=>'Enter Some Details'])  !!}
    </div>
    <div class="form-group">
        <label for="recaptcha">{{ t('Type these words') }}<small>*</small></label>
        {!! Recaptcha::render() !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Report',['class'=>'btn btn-danger']) !!}
    </div>
    {!!  Form::close()  !!}
@endsection