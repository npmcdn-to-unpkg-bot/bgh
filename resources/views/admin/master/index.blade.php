<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ siteSettings('siteName') }} | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
    {!! HTML::style('static/admin/css/main.css') !!}
    {!! HTML::style('static/admin/css/custom.css') !!}

    <!--[if lt IE 9]>
    {!! HTML::style('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
    {!! HTML::style('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->


    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <!-- BOOTSTRAP -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    {!! HTML::script('static/admin/main.js') !!}

    <!-- notificaciones toaster -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" ></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">

        // notificaciones
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "1500",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }

    </script>

</head>
<body class="skin-purple sidebar-mini">
@include('admin.master.notices')
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ url("admin") }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b><?=substr(siteSettings('siteName'),0,1) ?></b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>{{ siteSettings('siteName') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

        </nav>
    </header>

    <aside class="main-sidebar">
        @include('admin/master/sidebar')
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $title }} @if(Request::is('admin')) <small>Version {{ config('version.version') }}</small>@endif</h1>
        </section>
        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>

    <footer class="main-footer">
        <strong>{{ siteSettings('siteName') }} &copy; {{ date('Y') }}.</strong>
        All rights reserved.
    </footer>
    <div class="control-sidebar-bg"></div>
</div>




@yield('extra-js')
</body>
</html>