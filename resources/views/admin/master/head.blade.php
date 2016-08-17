<head>
    <meta charset="UTF-8">
    <title>{{ siteSettings('siteName') }} | {{ t('Admin') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {!! HTML::style('static/plugins/Ionicons/css/ionicons.min.css') !!}

    {!! HTML::style('static/plugins/font-awesome/css/font-awesome.min.css') !!}

    {!! HTML::style('static/plugins/normalize-css/normalize.css') !!}

    <!--[if lt IE 9]>
    {!! HTML::style('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
    {!! HTML::style('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->

    {!! HTML::script('static/plugins/jquery/dist/jquery.min.js') !!}

    {!! HTML::script('static/plugins/jquery-ui/jquery-ui.min.js') !!}
    {!! HTML::style('static/plugins/jquery-ui/themes/smoothness/jquery-ui.css') !!}

    {!! HTML::script('static/plugins/bootstrap/dist/js/bootstrap.min.js') !!}

    {{-- busco el de static porque está recompilado mediante el gulp y el \resources\assets\sass --}}
    {!! HTML::style('static/css/bootstrap.min.css') !!}
    {{-- {!! HTML::style('static/plugins/bootstrap/dist/css/bootstrap.min.css') !!} --}}
    {{-- {!! HTML::style('static/plugins/bootstrap/dist/css/bootstrap-theme.min.css') !!} --}}

    {!! HTML::script('static/plugins/toastr/toastr.min.js') !!}
    {!! HTML::style('static/plugins/toastr/toastr.min.css') !!}

    {!! HTML::script('static/plugins/select2/dist/js/select2.min.js') !!}
    {!! HTML::style('static/plugins/select2/dist/css/select2.min.css') !!}

    {!! HTML::style('static/plugins/datatables/media/css/jquery.dataTables.css') !!}
    {!! HTML::script('static/plugins/datatables/media/js/jquery.dataTables.js') !!}

    {!! HTML::script('static/plugins/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js') !!}
    {!! HTML::style('static/plugins/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css') !!}

    {!! HTML::script('static/plugins/raphael/raphael.min.js') !!}

    {!! HTML::script('static/plugins/morris.js/morris.min.js') !!}

    {!! HTML::script('static/plugins/nestedSortable/jquery.ui.nestedSortable.js') !!}

    {!! HTML::script('static/plugins/jquery.pin/jquery.pin.js') !!}

    {!! HTML::script('static/plugins/headroom.js/dist/headroom.min.js') !!}

    {!! HTML::script('static/plugins/jquery-form/jquery.form.js') !!}


    {!! HTML::style('static/admin/css/animate.css') !!}
    {!! HTML::style('static/admin/css/AdminLTE.css') !!}
    {!! HTML::style('static/admin/css/custom.css') !!}

    {!! HTML::script('static/admin/js/adminLTE.js') !!}
    {!! HTML::script('static/admin/main.js') !!}

    @yield('extra-css')

    <style type="text/css">
  /*
      body{
        padding-top: 50px;
      }*/

      .fixed {
          position: fixed;
          z-index: 10;
          right: 0;
          left: 0;
          top: 0;
      }

    </style>

</head>