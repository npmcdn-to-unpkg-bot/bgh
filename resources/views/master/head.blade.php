<!doctype html>
<html class="js csstransitions" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', e($title)) - {{ siteSettings('siteName') }}</title>

    @yield('meta', '<meta name="description" content="'.siteSettings('description').'">')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <!-- TIMEZONE: <?=Config::get('app.timezone')?> -->
    <!-- LOCALE: <?=Config::get('app.locale')?> -->
    <!-- SERVERTIME: <?=date('m/d/Y h:i:s a', time())?> -->
    <!--[if IE 8]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    {!! HTML::style('static/css/style.css') !!}

    {!! HTML::style('static/css/normalize.css') !!}
    {!! HTML::style('static/css/bootstrap.min.css') !!}

    {!! HTML::script('static/js/modernizr.custom.js') !!}

    @yield('style')

    @yield('extra-css')

</head>