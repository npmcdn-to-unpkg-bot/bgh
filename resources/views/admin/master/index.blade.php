<!DOCTYPE html>
<html>
@include('admin/master/head')
<body class="skin-purple sidebar-mini">
@include('admin.master.notices')
<div class="wrapper">

    <header id="header" class="main-header">

        <a href="{{ url("admin") }}" class="logo ">
            <span class="logo-mini"><b><?=substr(t('Admin'),0,1) ?></b></span>
            <span class="logo-lg"><b>{{ t('Admin') }}</b></span>
        </a>

        <nav class="navbar navbar-static-top " role="navigation">

            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="container22">
                <div class="navbar-header">
                  <a href="../../index2.html" class="navbar-brand"><b>{{ siteSettings('siteName') }}</b><?=siteSettings('siteSubname')?></a>
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                  </button>
                </div>

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
                        <img src="{{ Resize::img(auth()->user()->avatar,'mainAvatar') }}" class="user-image" alt="{{ auth()->user()->username }}">
                        <span class="hidden-xs">{{ auth()->user()->fullname }}</span>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="user-header">
                          <img src="{{ Resize::img(auth()->user()->avatar,'mainAvatar') }}" class="img-circle" alt="{{ auth()->user()->username }}">
                          <p>
                            {{ auth()->user()->fullname }}
                            <small>{{ auth()->user()->username }}</small>
                          </p>
                        </li>
                      {{--   <li class="user-body">
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
                        </li> --}}
                        <li class="user-footer">
                          <div class="pull-left">
                            <a href="{{ route('users.settings') }}" class="btn btn-default btn-flat">{{ t('Profile Settings') }}</a>
                          </div>
                          <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-info btn-flat">{{ t('Logout') }}</a>
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
        <section class="content">
            @yield('content')
        </section>
    </div>

    <footer class="main-footer">
        <strong>{{ siteSettings('siteName') }} &copy; {{ date('Y') }}.</strong>
        All rights reserved.
    </footer>
    <div class="control-sidebar-bg"></div>
</div>
</body>
</html>
@include('admin/master/js')
@yield('extra-js')