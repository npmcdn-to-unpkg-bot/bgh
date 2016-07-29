@extends('admin/master/index')

@section('extra-css')

    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">

    {!! HTML::style('static/admin/plugins/jQuery-File-Upload/css/jquery.fileupload.css') !!}
    {!! HTML::style('static/admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui.css') !!}
    {{-- {!! HTML::style('static/admin/plugins/jQuery-File-Upload/css/jquery.fileupload-noscript.css') !!} --}}
    {{-- {!! HTML::style('static/admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui-noscript.css') !!} --}}

@endsection

@section('content')

    <form id="fileupload" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>{{ t('Add files...') }}</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>{{ t('Start upload') }}</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>{{ t('Cancel upload') }}</span>
                </button>

                <div class="form-group">
                    {!! Form::label('tags', 'Tags') !!}
                    <select name="tags[]" class="form-control tagging" placeholder="Tag to make your photo easier to find..." multiple="multiple"></select>
                </div>
                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', null,['class' => 'form-control', 'placeholder' => 'Description']) !!}
                </div>

                <div class="col-md-12 fileupload-progress fade">
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <div class="progress-extended">&nbsp;</div>
                </div>

            </div>

            <div role="presentation"><div class="row files"></div></div>

    </form>


    <!-- The blueimp Gallery widget -->
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>



    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <div class="clearfix template-upload fade">
                <hr/>
                <div class="col-md-3">
                <p>
                <span class="preview"> </span>
                </p>
                </div>
                <div class="col-md-5"> <p>
                </p>
                </div>
                <div class="col-md-3">
                <p>
                {% if (!i && !o.options.autoUpload) { %}
        <button class="btn btn-primary start" disabled>
        <i class="glyphicon glyphicon-upload"></i>
                <span>{{ t('Start') }}</span>
                </button>
                {% } %}
        {% if (!i) { %}
        <button class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>{{ t('Cancel') }}</span>
                </button>
                {% } %}
        <div class="size">{{ t('Processing') }}</div>
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </p>

        </div>
        </div>

        {% } %}
    </script>
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <hr>
        <div class="clearfix template-download fade">

                <div class="col-md-12">
                <div class="col-md-3">
                {% if (file.title) { %}
        <p><span class="label label-danger">{{ t('Rejected') }}</span></p>
        {% } %}
        {% if (file.tags) { %}
        <p><span class="label label-danger">{{ t('Rejected') }}</span></p>
        {% } %}
        {% if (file.error) { %}
        <p><span class="label label-danger">{{ t('Rejected') }}</span></p>
        {% } %}
        {% if (file.success) { %}
        <p><img src="{%=file.thumbnail%}" style="max-width:200px"/></p>
                {% } %}
        </div>
        <div class="col-md-5">
                {% if (file.title) { %}
        <p>{%=file.title%}</p>
        {% } %}
        {% if (file.tags) { %}
        <p>{%=file.tags%}</p>
        {% } %}
        {% if (file.error) { %}
        <p>{%=file.error%}</p>
        {% } %}
        {% if (file.success) { %}
        <p>{{ t('uploaded successful') }}</p>
        <p><a href="{%=file.successSlug%}">{%=file.successTitle%}</a></p>
        {% } %}
        </div>
        <div class="col-md-3">
                {% if (file.success) { %}
        <a class="btn btn-success" href="{%=file.successSlug%}" target="_blank">
                <i class="glyphicon glyphicon-new-window"></i>
                <span>{{ t('Visit') }}</span>
                </a>
                {% } %}
        </div>
        </div>
        </div>
        {% } %}
    </script>


@endsection

@section('extra-js')


    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/vendor/jquery.ui.widget.js') !!}

    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- blueimp Gallery script -->
    <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>

    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.iframe-transport.js') !!}
    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload.js') !!}
    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-process.js') !!}

    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-image.js') !!}
    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-audio.js') !!}
    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-video.js') !!}

    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-validate.js') !!}
    {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-ui.js') !!}
    {{-- {!! HTML::script('static/admin/plugins/jQuery-File-Upload/js/jquery.fileupload-jquery-ui.js') !!} --}}



    <script>

        $(function () {
            'use strict';

            var uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                        data = $this.data();
                    $this
                        .off('click')
                        .text('Abort')
                        .on('click', function () {
                            $this.remove();
                            data.abort();
                        });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });



            // $("#fileupload").fileupload({
            //     type: "POST",
            //     previewMaxHeight: 210,
            //     previewMaxWidth: 210,
            //     limitConcurrentUploads:1,
            //     limitMultiFileUploads: 1,
            //     sequentialUploads: true,
            //     acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            // });
            //
            //
            $('#fileupload').fileupload({
                // url: url,
                type: "POST",
                // previewMaxHeight: 210,
                // previewMaxWidth: 210,
                limitConcurrentUploads:1,
                limitMultiFileUploads: 1,
                sequentialUploads: true,
                // dataType: 'json',
                autoUpload: false,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mov|mp4)$/i,
                maxFileSize: 99999000,
                // disableImageResize: /Android(?!.*Chrome)|Opera/
                //     .test(window.navigator.userAgent),
                // previewCrop: true
            }).on('fileuploadadd', function (e, data) {
                data.context = $('<div/>').appendTo('#files');
                $.each(data.files, function (index, file) {
                    var node = $('<p/>')
                            .append($('<span/>').text(file.name));
                    if (!index) {
                        node
                            .append('<br>')
                            .append(uploadButton.clone(true).data(data));
                    }
                    node.appendTo(data.context);
                });
            }).on('fileuploadprocessalways', function (e, data) {
                var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
                if (file.preview) {
                    node
                        .prepend('<br>')
                        .prepend(file.preview);
                }
                if (file.error) {
                    node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
                }
                if (index + 1 === data.files.length) {
                    data.context.find('button')
                        // .text('Upload')
                        .prop('disabled', !!data.files.error);
                }
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }).on('fileuploaddone', function (e, data) {
                $.each(data.result.files, function (index, file) {
                    if (file.url) {
                        var link = $('<a>')
                            .attr('target', '_blank')
                            .prop('href', file.url);
                        $(data.context.children()[index])
                            .wrap(link);
                    } else if (file.error) {
                        var error = $('<span class="text-danger"/>').text(file.error);
                        $(data.context.children()[index])
                            .append('<br>')
                            .append(error);
                    }
                });
            }).on('fileuploadfail', function (e, data) {
                $.each(data.files, function (index) {
                    var error = $('<span class="text-danger"/>').text('File upload failed.');
                    $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                });
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
        });



        $(function(){

            $('.tagging').select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            });


        });
    </script>
@endsection