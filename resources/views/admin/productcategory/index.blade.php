@extends('admin/master/index')

@section('content')
    <div class="row">
        <div class="col-md-10">
            {!! Form::open()  !!}
            <div class="form-group">
                <label for="addnew">New</label>
                {!! Form::text('addnew',null,['class'=>'form-control','placeholder'=>'Name of product category'])  !!}
            </div>
            <div class="form-group">
                {!! Form::submit('Add',['class'=>'btn btn-success'])  !!}
            </div>
            {!! Form::close()  !!}


            <div class="page-header">
                <h3 class="content-heading">Categories
                    <small>laralalalal </small>
                </h3>
            </div>

            <div class='area' id='adminChannels'>
                <ol class='sortable list channelList list-group'>

                    <?php

                    $curDepth = 0;
                    $counter = 0;

                    foreach (\App\Models\ProductCategory::orderBy('lft', 'asc')->get() as $category):
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
                        <!-- <i class="fa fa-arrows-alt pull-left">&nbsp;</i> -->

                        <a href="{{ route('admin.productcategories.order', ['id' => $category->id]) }}" class="pull-right">ITEMS&nbsp;({{ sizeof($category->products) }})</a>
                        <span class="pull-right">&nbsp;|&nbsp;</span>
                        <a href="" class="pull-right" data-toggle="modal" data-target="#categoryMode-{{ $category->id }}">QUICK</a>
                        <span class="pull-right">&nbsp;|&nbsp;</span>
                        <a href="{{ route('admin.productcategories.edit', ['id' => $category->id]) }}" class="pull-right">EDIT</a>

                        <div class='info'>
                            <span class='channel channel-1'>{{ $category->name }}</span>
                        </div>

                    <?php $counter++; ?>

                    <?php endforeach;

                    echo str_repeat("</li></ol>", $curDepth), "</li>";
                    ?>
                </ol>
            </div>
        </div>

        @foreach (\App\Models\ProductCategory::orderBy('lft','asc')->get() as $category)
            <div class="modal fade" id="categoryMode-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Edit</h4>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['url'=>'admin/productcategories/update']) !!}
                            <div class="form-group">
                                {!! Form::text('id',$category->id,['class'=>'hidden']) !!}
                                <label for="addnew">Product Category Name</label>
                                {!! Form::text('name',$category->name,['class'=>'form-control','placeholder'=>'Name of category','required'=>'required']) !!}
                            </div>


                            <div class="form-group">
                                <label for="slug">Slug ( url of category )
                                    <small>English characters are allowed in url, space is seperate by dash</small>
                                </label>
                                {!! Form::text('slug',$category->slug,['class'=>'form-control','placeholder'=>'Slug','required'=>'required']) !!}
                            </div>
                            @if($category->id == 1 || $category->name == 'Uncategorized')
                                <p>You can't delete this category, this is default category in which images will go, if not category selected</p>
                            @else
                                <div class="form-group">
                                    <label for="addnew">Delete this product category
                                        <small> ( At your own risk )</small>
                                    </label><br/>
                                    {!! Form::checkbox('delete',true,false,['rel' => 'delete']) !!}
                                </div>
                            @endif
                            <div class="form-group">
                                <p><strong>Shift images from this product category to new product category</strong></p>
                                <select name="shiftCategory" class="form-control" disabled rel="shiftToCategory">
                                    @foreach(\App\Models\ProductCategory::whereNotIn('id', [$category->id])->orderBy('lft','asc')->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                {!! Form::submit('Update',['class'=>'btn btn-success']) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach
@endsection

@section('extra-js')
            <script type="text/javascript">

                $(function () {
                    $("[rel=delete]").click(function () {
                        $("[rel=shiftToCategory]").attr("disabled", false);
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
