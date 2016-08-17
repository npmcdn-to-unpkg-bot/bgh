<!DOCTYPE html>
<html>
  @include('admin/master/head')
  <body class="skin-purple sidebar-mini">
    @include('admin.master.notices')
    @yield('content')
  </body>
</html>
@yield('extra-js')