@extends('master/index')

@section('content')
<h1 class="content-heading">{{ t('FAQ') }}</h1>
<p>
    {!! siteSettings('faq') !!}
</p>
@endsection