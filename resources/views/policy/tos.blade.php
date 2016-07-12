@extends('master/index')

@section('content')
<h3 class="content-heading">{{ t('Terms Of Services') }}</h3>
<p>
    {!! siteSettings('tos')  !!}
</p>
@endsection