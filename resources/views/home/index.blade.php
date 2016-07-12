@extends('master/index')

@section('content')

    <div class="row">
        <h1>{{ siteSettings('siteName') }}</h1>
        <h2>{{ siteSettings('description') }}</h2>
    </div>
    <div class="row">
        <div class="col-md-5 col-sm-5">
        </div>
    </div>

@endsection
