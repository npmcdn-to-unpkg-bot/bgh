@extends('admin/master/index')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">{{ t('Create') }}</button>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">

            <div class='categories-sortable-list area' id='adminChannels'>
                <ol class='sortable list channelList list-group'>

                    <?php

                    $curDepth = 0;
                    $counter = 0;

                    foreach ($categories as $category):
                    if ($category->depth == $curDepth)
                    {
                        if ($counter > 0) echo "</li>";
                    }
                    elseif ($category->depth > $curDepth)
                    {
                        echo "<ol>";
                        $curDepth = $category->depth;
                    }
                    elseif ($category->depth < $curDepth)
                    {
                        echo str_repeat("</li></ol>", $curDepth - $category->depth), "</li>";
                        $curDepth = $category->depth;
                    }

                    ?>
                    <li id='channel_{{ $category->id }}' data-id='{{ $category->id }}' class="list-group-item" style="cursor: move">
                        <div class='info'>
                            <span class='channel channel-1'>{{ $category->name }}<a href="{{ route('admin.productcategories.items', ['id' => $category->id]) }}"><small>&nbsp;({{ sizeof($category->products) }} items)</small></a></span>
                            <div class="btn-group pull-right btn-group-sm" role="group" aria-label="Actions">
                            <a href="{{ route('products', ['category' =>  $category->link]) }}" target="_blank" class="btn btn-default" rel="view"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('admin.productcategories.items', ['id' => $category->id]) }}" class="btn btn-default"><i class="fa fa-cubes"></i></a>
                                <a href="{{ route('admin.productcategories.edit', ['id' => $category->id]) }}" class="btn btn-default"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.productcategories.edit', ['id' => $category->id]) }}" class="btn btn-default" rel="delete"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>

                    <?php $counter++; ?>

                    <?php endforeach;

                    echo str_repeat("</li></ol>", $curDepth), "</li>";
                    ?>
                </ol>
            </div>
        </div>

    </div>

    {{ Form::open(['method' => 'DELETE', 'name' => 'delete']) }}
    {{ Form::close() }}

    <!-- Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['role' => 'form'])  !!}
                    <div class="modal-header">
                        <button type="button" class="close"
                           data-dismiss="modal">
                               <span aria-hidden="true">&times;</span>
                               <span class="sr-only">{{ t('Close') }}</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            {{ t('Create') }}
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">{{ t('Name') }}</label>
                            {!! Form::text('name',null,['class'=>'form-control','placeholder'=>t('Name')])  !!}
                        </div>
                        <div class="form-group">
                            <label for="slug">{{ t('Slug') }}</label>
                            {!! Form::text('slug',null,['class'=>'form-control','placeholder'=>t('Slug')])  !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Close') }}</button>
                        {!! Form::submit(t('Accept'),['class'=>'btn btn-primary'])  !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('extra-js')

    <script type="text/javascript">

        $(function () {


            $('.btn[rel="delete"]').on('click',function(e) {
                e.preventDefault();

                var $this = $(this);

                $('form[name="delete"]').attr('action',$this.attr('href'));

                BootstrapDialog.show({
                    message: 'Confirm Delete?',
                    buttons: [{
                        label: 'Confirm',
                        cssClass: 'btn-primary',
                        action: function(){
                            $('form[name="delete"]').attr('action',$this.attr('href')).submit();
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

        $("#adminChannels .channelList").nestedSortable({
            forcePlaceholderSize: true,
            disableNestingClass: 'mjs-nestedSortable-no-nesting',
            handle: 'div',
            helper: 'clone',
            items: 'li',
            maxLevels: 0,
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            update: function () {
                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/productcategories/reorder') }}",
                    data: {tree: $("#adminChannels .channelList").nestedSortable("toArray", {startDepthCount: -1})},
                    globalLoading: true
                });
            }
        });

    </script>

@endsection
