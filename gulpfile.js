var elixir = require('laravel-elixir');
// Configure elixir to use different directories

// elixir.config.assetsDir = 'app/assets/';
// elixir.config.cssOutput = 'public_html/css';
// elixir.config.jsOutput = 'public/js';
// elixir.config.bowerDir = 'vendor/bower_components';
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    mix.sass("bootstrap.scss", 'public/static/css/bootstrap.min.css').styles([
        "css/uploader.css",
        "css/bootstrap.min.css",
        "css/alert.css",
        "plugins/fontawesome/css/font-awesome.css",
        'plugins/jt.timepicker/jquery.timepicker.css',
        'plugins/select2/dist/css/select2.min.css',
        'plugins/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
        'plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
        'plugins/jquery-file-upload/css/jquery.fileupload.css',
        'plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
        'plugins/lightgallery/dist/css/lightgallery.min.css',
    ], 'public/static/css/main.css', 'public/static');

    mix.styles(["static/css/style.css"], 'public/static/css/style.min.css', 'public');

    mix.copy('public/static/plugins/bootstrap-sass/assets/fonts/bootstrap', 'public/static/fonts');
    mix.copy('public/static/plugins/gmaps/gmaps.min.js', 'public/static/js');
    mix.copy('public/static/plugins/fontawesome/fonts', 'public/static/fonts');
    mix.copy('public/static/plugins/lightgallery/dist/img', 'public/static/img');
    mix.copy('public/static/plugins/lightgallery/dist/fonts', 'public/static/fonts');

    mix.scripts([
        'plugins/jquery/dist/jquery.js',
        'plugins/jquery-ui/jquery-ui.js',
        'plugins/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'plugins/blueimp-load-image/js/load-image.all.min.js',
        'plugins/blueimp-tmpl/js/tmpl.js',
        'plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js',
        'plugins/jquery-file-upload/js/jquery.fileupload.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-process.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-image.js',
        'plugins/jquery-file-upload/js/cors/jquery.postmessage-transport.js',
        'plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js',
        'plugins/jquery-resize-image-to-parent/jquery.resizeimagetoparent.min.js',
        'plugins/select2/dist/js/select2.full.min.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'plugins/jt.timepicker/jquery.timepicker.js',
        'plugins/jquery-timeago/jquery.timeago.js',
        'plugins/color-thief/src/color-thief.js',
        'plugins/jquery.lazyload/jquery.lazyload.js',
        'plugins/jquery.lazyload/jquery.scrollstop.js',
        'plugins/lightgallery/dist/js/lightgallery-all.min.js',
        'plugins/canvasResize/exif.js',
        'plugins/canvasResize/binaryajax.js',
        'plugins/canvasResize/zepto.min.js',
        'js/canvasResize.js',
        'js/json2html.js',
        'js/jquery.json2html.js',
        'plugins/autosize/dist/autosize.min.js',
        'js/alert.js',
        'js/app.js',
    ], 'public/static/js/main.js', 'public/static');
    mix.scripts(['static/js/custom.js'], 'public/static/js/custom.min.js', 'public');

    /**
     * Admin Panel Template requirement's starts from here.
     */

    mix.styles([
        "css/uploader.css",
        "css/bootstrap.min.css",
        "css/alert.css",
        "admin/css/AdminLTE.min.css",
        "admin/css/custom.css",
        "admin/css/skins/skin-purple.min.css",
        "plugins/morris.js/morris.css",
        'plugins/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
        "plugins/datatables/media/css/dataTables.bootstrap.min.css",
        "admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css",
        'plugins/jt.timepicker/jquery.timepicker.css',
        'plugins/select2/dist/css/select2.min.css',
        'plugins/jquery-file-upload/css/jquery.fileupload.css',
        'plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
    ], 'public/static/admin/css/main.css', 'public/static');

    mix.scripts([
        'plugins/jquery/dist/jquery.js',
        'plugins/jquery-ui/jquery-ui.js',
        'plugins/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'plugins/blueimp-load-image/js/load-image.all.min.js',
        'plugins/blueimp-tmpl/js/tmpl.js',
        'plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js',
        'plugins/jquery-file-upload/js/jquery.fileupload.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-process.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-image.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-ui.js',
        'plugins/jquery-file-upload/js/cors/jquery.postmessage-transport.js',
        'plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js',
        'plugins/footable/dist/footable.min.js',
        'plugins/raphael/raphael-min.js',
        'plugins/morris.js/morris.min.js',
        'plugins/datatables/media/js/jquery.dataTables.min.js',
        'plugins/datatables/media/js/dataTables.bootstrap.min.js',
        'plugins/footable/dist/footable.all.min.js',
        'plugins/select2/dist/js/select2.full.min.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'plugins/jt.timepicker/jquery.timepicker.js',
        'plugins/jquery-timeago/jquery.timeago.js',
        'admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js',
        'admin/js/sortable.js',
        'admin/js/app.min.js',
        'admin/js/custom.js',
    ], 'public/static/admin/main.js', 'public/static');

    mix.copy('public/static/plugins/datatables/media/images', 'public/static/admin/images');
    mix.copy('public/static/plugins/jquery-file-upload/img', 'public/static/admin/img');
    mix.copy('public/static/plugins/bootstrap-sass/assets/fonts/bootstrap', 'public/static/admin/fonts');
    mix.copy('public/static/plugins/fontawesome/fonts', 'public/static/admin/fonts');

});