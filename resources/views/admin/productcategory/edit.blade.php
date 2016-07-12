@extends('admin/master/index')

@section('content')
     <div class="row">
        <div class="col-md-10">

            {!! Form::open(array('action' => array('Admin\ProductCategory\ProductCategoryController@update', $category->id))) !!}

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
                    <label for="addnew">Delete
                    </label><br/>
                    {!! Form::checkbox('delete',true,false,['rel' => 'delete']) !!}
                </div>
            @endif
            <div class="form-group">
                <p><strong>Shift images to new</strong></p>
                <select name="shiftCategory" class="form-control" disabled rel="shiftToCategory">
                    @foreach(\App\Models\ProductCategory::whereNotIn('id', [$category->id])->orderBy('lft','asc')->get() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::label('products', 'Products:') !!}
                <select id="products" class="form-control input-lg tagging" multiple="multiple" name="products[]">
                    @foreach($products as $p)
                        <option value="{{ $p['id'] }}" {{ $p['value'] ? "selected=\"selected\"" : "" }}>{{ $p['text'] }}</option>
                    @endforeach
                </select>
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

            $("[rel=delete]").click(function () {
                $("[rel=shiftToCategory]").attr("disabled", false);
            });

            $("[rel=close]").click(function () {
                location.href='{{ url('admin/productcategories/') }}';
            });

            $('#products').select2({
                placeholder: 'product name..'
            });


        });

    </script>
@endsection
