<?php
// var_dump($ancestors);
?>

@extends('master/index')

@section('content')

    <a class="breadcrum" href="{{ route('category') }}/">{{ t('Catalog') }}</a><span>&nbsp;/&nbsp;</span>
    @foreach($ancestors as $ancestor)
        <a class="breadcrum" href="{{ route('category') }}/{{ $ancestor->link }}">{{ str_limit($ancestor->name, 40) }}</a><span>&nbsp;/&nbsp;</span>
    @endforeach

    <h1 class="content-heading">{{ $title }}</h1>

@endsection


@section('extra-js')

    <script>
        (function() {

        })();
    </script>
@endsection