@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            <p><strong>All product will have same category,tags as you select below and local product name is taken as title</strong></p>
            <form id="fileupload" action="" method="POST" enctype="multipart/form-data">

                <div class="fileupload-buttonbar">
                    <div class="form-group">
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>{{ t('Add files...') }}</span>
                    <input type="file" name="files[]" accept="product/*" multiple>
                </span>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>{{ t('Cancel upload') }}</span>
                        </button>
                        <button type="submit" class="btn btn-primary start">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start upload</span>
                        </button>
                    </div>

                    <div class="form-group">
                        {!! Form::label('is_microsite', 'Is Microsite Product') !!}
                        {!! Form::select('is_microsite',['1' => 'Yes', '0' => 'No'],null,['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">

                    </div>
                    <div class="form-group">
                        {!! Form::label('tags', 'Tags') !!}
                        <select name="tags[]" class="form-control tagging" placeholder="Tag to make your photo easier to find..." multiple="multiple"></select>
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
        </div>
 </div>
@endsection

@section('extra-js')
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
        <p>{{ t('Your Product is uploaded successfully') }}</p>
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
    <script>
        $(function(){
            $('.tagging').select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            });

            $("#fileupload").fileupload({
                type: "POST",
                previewMaxHeight: 210,
                previewMaxWidth: 210,
                limitConcurrentUploads:1,
                limitMultiFileUploads: 1,
                sequentialUploads: true,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            });
        });
    </script>
@endsection