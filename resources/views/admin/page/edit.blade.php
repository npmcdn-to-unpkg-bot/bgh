@extends('admin.master.index')
@section('content')


    {!! HTML::style('static/admin/plugins/grideditor/grideditor.css') !!}

    <style>

        .progress.active .progress-bar {
            -webkit-transition: none !important;
            transition: none !important;
        }

        .loading{
            display: none;
        }

    </style>



        {!! Form::open(['method' => 'PATCH', 'files' => true, 'id' => 'mainForm']) !!}

            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $page->title, ['class' => 'form-control input-lg', 'placeholder' => 'Title of Product']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', $page->slug, ['class' => 'form-control input-lg', 'placeholder' => 'Slug']) !!}
            </div>

            <div class="form-group form-group-lg">
                {!! Form::label('tags', 'Tags') !!}
                <select class="form-control input-lg tagging" multiple="multiple" name="tags[]">
                    @foreach(explode(',',$page->tags) as $tag)
                        @if($tag)
                            <option selected="selected">{{ $tag }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div id="myGrid">
                <?php echo $page->html ?>
            </div>

            {!! Form::button('Update', ['class' => 'btn btn-success btn-lg', 'id' => 'btn_submit']) !!}
            {!! Form::button('Delete', ['class' => 'btn btn-danger btn-lg', 'id' => 'btn_delete']) !!}

            <i class="fa fa-cog fa-spin fa-fw loading fa-2x"></i>
            <div class="progress progress-striped active" style="display:none;">
                <div class="progress-bar progress-bar-success" style="width:0%"></div>
            </div>

        {!! Form::close() !!}


        {{ Form::open(['method' => 'DELETE', 'route' => ['admin.pages.edit', $page->id], 'name' => 'delete']) }}
        {{ Form::close() }}


@endsection

@section('extra-js')


    <!-- Ckeditor js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>


    {!! HTML::script('static/admin/plugins/grideditor/jquery.grideditor.js') !!}

    {!! HTML::script('static/admin/js/jquery.form.min.js') !!}

    <script>

        $(document).ready(function() {


            $('#myGrid').gridEditor({
                new_row_layouts: [[12], [6,6], [9,3], [4,4,4]],
                valid_col_sizes: [3, 4, 6, 9, 12],
                content_types: ['ckeditor'],
                 row_tools: [{
                    title: 'Set background image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.row').css('background-image', 'url(http://placekitten.com/g/300/300)');
                        }
                    }
                }],
                col_tools: [{
                    title: 'Set background2 image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.row').css('background-image', 'url(http://placekitten.com/g/300/300)');
                        }
                    }
                }],
            });



            $(".tagging").select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            })


            $('#mainForm').submit(function(e) {
                e.preventDefault();
                var $this = $(this);

                $(this).ajaxSubmit({
                    url: '{{ route('admin.pages.edit',['id' => $page->id]) }}',
                    type: 'post',
                    dataType: 'json',
                    beforeSubmit: function() {
                        console.log('beforeSubmit');
                        $(".progress").show();
                        $this.find('button, input[type=submit]').prop('disabled',true);
                        $this.find('.loading').show();
                    },
                    uploadProgress: function (event, position, total, percentComplete){
                        $(".progress-bar").animate({width: percentComplete + '%'},50).html(percentComplete + '%');
                    },
                    success:function (data){
                        // reb corre con el response 200 a 300 si esta el dataType json llega con los datos, sino hay que buscar if200 en el complete que corre siempre
                        console.log('success');
                        console.log(data);
                        toastr["success"](data);
                    },
                    complete:function (data){ // corre siempre cuando termina, por error o success
                        // console.log('complete');
                        $this.find('button, input[type=submit]').prop('disabled',false);
                        $this.find('.loading').hide();
                        $(".progress").hide();
                    },
                    error: function(data){
                        console.log('error');
                        if(data.status==422){
                            var errors = data.responseJSON;
                            $.each(errors, function(index, value) {
                                toastr["error"](value);
                            });
                        }
                        else{
                            toastr["error"]('error al enviar el formulario');
                            console.log(data.status);
                            console.log(data.responseText);
                        }

                    }

                });
                return false;

            });



            $('#btn_submit').on('click',function() {


                var html = $('#myGrid').gridEditor('getHtml');


                $('<input>').attr({
                    type: 'hidden',
                    id: 'html',
                    name: 'html'
                }).appendTo('#mainForm').val(html);


                $('#mainForm').submit();

            });


            $('#btn_delete').on('click',function() {

                BootstrapDialog.show({
                    message: 'Confirm Delete?',
                    buttons: [{
                        label: 'Confirm',
                        cssClass: 'btn-primary',
                        action: function(){
                            $('form[name="delete"]').submit();
                        }
                    }, {
                        label: 'Close',
                        action: function(dlg){
                            dlg.close();
                        }
                    }]
                });

            });



        });

    </script>


@endsection