@extends('master/index')

@section('content')
<h1 class="content-heading">{{ t('About Us') }}</h1>
<p>
    {!! siteSettings('about')  !!}
</p>
@endsection