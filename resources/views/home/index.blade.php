@extends('master/index')

@section('content')

    <div class="row">
        <h1>{{ siteSettings('siteName') }}</h1>
        <h2>{{ siteSettings('description') }}</h2>
    </div>
    <div class="row">
        <div class="col-md-5 col-sm-5">


        </div>



        <!--a href="{{ route('gallery') }}" class="btn btn-info btn-lg" style="margin-bottom: 10px">{{ t('Browse Gallery') }}</a-->
        <!--a href="{{ route('login') }}" class="btn btn-info btn-lg" style="margin-bottom: 10px">{{ t('Login To Site') }}</a-->
    </div>

@endsection
