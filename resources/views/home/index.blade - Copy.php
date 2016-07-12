<!DOCTYPE html>
<html class="full" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ siteSettings('siteName') }}</title>
    <meta name="description" content="{{ siteSettings('description') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <!--[if IE 8]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    {!! HTML::style('static/css/main.css') !!}
    {!! HTML::style('static/css/style.min.css') !!}
    <style type="text/css">
        html,body {
            @foreach(getFeaturedImage() as $featuredImage)   background: url({{ Resize::image($featuredImage,'coverImage') }}) no-repeat center center fixed;
            @endforeach
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            height: 100%;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>
@include('master/navbar')
<div class="home-centerDiv">
    <h1>{{ siteSettings('siteName') }}</h1>

    <h2>{{ siteSettings('description') }}</h2>
    <!--a href="{{ route('gallery') }}" class="btn btn-info btn-lg" style="margin-bottom: 10px">{{ t('Browse Gallery') }}</a-->
    <!--a href="{{ route('login') }}" class="btn btn-info btn-lg" style="margin-bottom: 10px">{{ t('Login To Site') }}</a-->
</div>
{!! HTML::script('static/js/main.js') !!}
{!! HTML::script('static/js/custom.min.js') !!}
</body>
</html>