<?php
    // var_dump(auth()->user());
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ siteSettings('siteName') }} | {{ t('Admin') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">



    {!! HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}

    {!! HTML::style('static/admin/css/normalize.css') !!}

    {!! HTML::style('static/admin/css/AdminLTE.css') !!}
    {!! HTML::style('static/admin/css/custom.css') !!}

    {!! HTML::style('static/admin/css/bootstrap-dialog.min.css') !!}

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

    <!-- notificaciones toaster -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" ></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    {!! HTML::script('static/admin/js/bootstrap-dialog.min.js') !!}

    {!! HTML::script('static/admin/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('static/admin/js/raphael.min.js') !!}
    {!! HTML::script('static/admin/js/morris.min.js') !!}
    {!! HTML::script('static/admin/js/adminLTE.js') !!}


    {!! HTML::script('static/admin/js/jquery.mjs.nestedSortable.js') !!}


    {!! HTML::script('static/admin/main.js') !!}



    <script type="text/javascript">

        // notificaciones
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-bottom-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "3000",
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
            <span class="logo-mini"><b><?=substr(t('Admin'),0,1) ?></b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>{{ t('Admin') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="container">
                <div class="navbar-header">
                  <a href="../../index2.html" class="navbar-brand"><b>{{ siteSettings('siteName') }}</b><?=siteSettings('siteSubname')?></a>
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                  </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                  <ul class="nav navbar-nav">
                    <li class="active222"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                      </ul>
                    </li>
                  </ul>
                  <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                      <input type="text" class="form-control" id="navbar-search-input" placeholder="{{ t('Search') }}">
                    </div>
                  </form>
                </div>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav">
                    <!-- <li class="dropdown messages-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                          <ul class="menu">
                            <li>
                              <a href="#">
                                <div class="pull-left">
                                  <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                </div>
                                <h4>
                                  Support Team
                                  <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                </h4>
                                <p>Why not buy a new awesome theme?</p>
                              </a>
                            </li>
                          </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                      </ul>
                    </li> -->

                    <li class="dropdown notifications-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                          <ul class="menu">
                            <li>
                              <a href="#">
                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                              </a>
                            </li>
                          </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                      </ul>
                    </li>

                    <li class="dropdown tasks-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                          <ul class="menu">
                            <li>
                              <a href="#">
                                <h3>
                                  Design some buttons
                                  <small class="pull-right">20%</small>
                                </h3>
                                <div class="progress xs">
                                  <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">20% Complete</span>
                                  </div>
                                </div>
                              </a>
                            </li>
                          </ul>
                        </li>
                        <li class="footer">
                          <a href="#">View all tasks</a>
                        </li>
                      </ul>
                    </li>
                    <li class="dropdown user user-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Resize::avatar(auth()->user(),'mainAvatar') }}" class="user-image" alt="{{ auth()->user()->username }}">
                        <span class="hidden-xs">{{ auth()->user()->fullname }}</span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="user-header">
                          <img src="{{ Resize::avatar(auth()->user(),'mainAvatar') }}" class="img-circle" alt="{{ auth()->user()->username }}">
                          <p>
                            {{ auth()->user()->fullname }}
                            <small>{{ auth()->user()->permission }}</small>
                          </p>
                        </li>
                        <li class="user-body">
                          <div class="row">
                            <div class="col-xs-4 text-center">
                              <a href="{{ route('user', ['username' => auth()->user()->username]) }}" target="_blank">View</a>
                            </div>
                            <div class="col-xs-4 text-center">
                              <a href="{{ route('user', ['username' => auth()->user()->username]) }}" target="_blank">View</a>
                            </div>
                            <div class="col-xs-4 text-center">
                              <a href="{{ route('user', ['username' => auth()->user()->username]) }}" target="_blank">View</a>
                            </div>
                          </div>
                        </li>
                        <li class="user-footer">
                          <div class="pull-left">
                            <a href="{{ route('users.settings') }}" class="btn btn-default btn-flat">{{ t('Profile Settings') }}</a>
                          </div>
                          <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat">{{ t('Logout') }}</a>
                          </div>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>

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