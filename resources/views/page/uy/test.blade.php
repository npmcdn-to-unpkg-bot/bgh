@extends('master/plain')

@section('meta')
   <meta name="description" content="{{ strlen($page->description) > 2 ? $page->description : $page->title.' '.siteSettings('description') }}">
   <meta name="keywords" content="{{ strlen($page->tags) > 2 ? $page->tags : $page->title }}">
   <meta property="og:title" content="{{ $page->title }} - {{ siteSettings('siteName') }}"/>
   <meta property="og:type" content="article"/>
   <meta property="og:url" content="{{ route('page', ['id' => $page->id, 'slug' => $page->slug]) }}"/>
   <meta property="og:description" content="{{ strlen($page->description) > 2 ? $page->description : $page->title.' '.siteSettings('description') }}"/>
   <meta property="og:page" content=""/>
   <meta name="author" content="{{ $page->user->fullname }}">
@endsection

@section('content')

   <h1 class="content-heading">UY TEST.BLADE {{ $page->title }}</h1>

   {!! $page->html !!}

@endsection