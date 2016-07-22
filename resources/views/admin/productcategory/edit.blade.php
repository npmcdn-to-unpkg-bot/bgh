@extends('admin/master/index')

@section('content')

     <div class="row">

      <div class="col-md-3">
            <ul class="list-group">
                <a href="#" class="list-group-item disabled">
                    Statics
                </a>
                <li class="list-group-item"><strong>Products</strong> {{ $category->products->count() }}</li>
                <li class="list-group-item"><strong>Uploaded At</strong> {{ $category->created_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Last Updated</strong> {{ $category->updated_at->diffForHumans() }} </li>
            </ul>
        </div>

        <div class="col-md-9">
            {!! Form::open(array('action' => array('Admin\ProductCategory\ProductCategoryController@update', $category->id))) !!}

                <div class="form-group">
                    <label for="name">Name</label>
                    {!! Form::text('name',$category->name,['class'=>'form-control','placeholder'=>'Name of category','required'=>'required']) !!}
                </div>

                <div class="form-group">
                    <label for="slug">Slug<small>English characters are allowed in url, space is seperate by dash</small></label>
                    {!! Form::text('slug',$category->slug,['class'=>'form-control','placeholder'=>'Slug','required'=>'required']) !!}
                </div>

                {!! Form::submit('Update',['class'=>'btn btn-success']) !!}
                <button type="button" class="btn btn-default" rel="close">Close</button>

            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('extra-js')
    <script type="text/javascript">

        (function($){
            $.fn.extend({
                select2_sortable: function(){
                    var select = $(this);
                    $(select).select2();
                    var ul = $(select).prev('.select2-container').first('ul');
                    ul.sortable({
                        placeholder : 'ui-state-highlight',
                        items       : 'li:not(.select2-search-field)',
                        tolerance   : 'pointer',
                        stop: function() {
                            $($(ul).find('.select2-search-choice').get().reverse()).each(function() {
                                var id = $(this).data('select2Data').id;
                                var option = select.find('option[value="' + id + '"]')[0];
                                $(select).prepend(option);
                            });
                        }
                    });
                }
            });
        }(jQuery));



        $(function () {

            $("[rel=close]").click(function () {
                location.href='{{ url('admin/productcategories/') }}';
            });

        });

    </script>
@endsection
