<!doctype html>
<html class="js" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', e($title)) - {{ siteSettings('siteName') }}</title>
    @yield('meta', '<meta name="description" content="'.siteSettings('description').'">')
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

    {!! HTML::style('static/css/normalize.css') !!}
    {!! HTML::style('static/fonts/font-awesome-4.3.0/css/font-awesome.min.css') !!}
    {!! HTML::style('static/css/demo.css') !!}
    {!! HTML::style('static/css/style1.css') !!}

    {!! HTML::script('static/js/modernizr-custom.js') !!}

</head>
<body  class="demo-1">


<div class="container">

    @yield('content')


</div>

@yield('extrafooter')
</body>
</html>
@yield('extra-js')