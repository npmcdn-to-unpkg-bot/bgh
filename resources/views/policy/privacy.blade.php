@extends('master/index')

@section('content')
<h1 class="content-heading">{{ t('Privacy Policy') }}</h1>
<p>
    {!! siteSettings('privacy')  !!}
</p>
@endsection