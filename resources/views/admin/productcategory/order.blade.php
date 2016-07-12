@extends('admin/master/index')

@section('content')
     <div class="row">
        <div class="col-md-10">

            {!! Form::open(['url'=>'admin/productcategories/update']) !!}
            <div class="form-group">
                {!! Form::text('id',$category->id,['class'=>'hidden']) !!}

                <a href="#" class="btn button">cerrar</a>
            </div>

            <div class="form-group">

                <div >
                    <ol id="items" class='sortable ui-sortable'>
                        @foreach($items as $item)
                            <li data-id="{{ $item->id }}">
                                <div>
                                    <img src="{{ Resize::img($item->main_image, 'sidebarProduct')  }}" alt="{{ $item->slug }}" class="display-image">
                                    <span>{{ $item->title }}</span>
                                    <button class="remove pull-right btn btn-warning">Remove</button>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

            </div>


            <div class="form-group">
                {!! Form::label('products', 'Products:') !!}

                {!! Form::select('products_list', [], null, ['class' => 'form-control', 'id' => 'products_list']) !!}
            </div>

            {!! Form::close() !!}
        </div>

    </div>


@endsection

@section('extra-js')
    <script type="text/javascript">

        $(function () {

            $("[rel=close]").on('click',function () {
                location.href='{{ url('admin/productcategories/') }}';
            });



            $('#products_list').select2({
                placeholder: 'Enter part of product name',
                ajax: {
                    dataType: 'json',
                    url: '{{ route('admin.productcategories.productlist') }}',
                    delay: 100,
                    data: function(params) {
                        // console.log(params.term);
                        return {
                            term: params.term
                        }
                    },
                    processResults: function (data, page) {
                        // console.log(data);
                        return {
                            results: data
                        };
                    }
                }
            }).on("change", function(e) {
                var obj = $("#products_list").select2("data");
                // console.log(obj[0]);
                $("#items").append('<li data-id="' + obj[0].id + '"><div><img src="' + obj[0].image + '" alt="' + obj[0].slug + '" class="display-image"><span>' + obj[0].text + '</span><button class="remove pull-right btn btn-warning">Remove</button></div></li>');
                save();
            });

            $("#items").on('click','.remove',function (e) {
                e.preventDefault();
                $(this).closest('li').animate({ height: 'toggle', opacity: 'toggle' }, 'fast',function(){
                    $(this).remove();
                    save();
                });
            });


        });

        $("#items").nestedSortable({
            forcePlaceholderSize: true,
            disableNestingClass: 'mjs-nestedSortable-no-nesting',
            handle: 'div',
            helper: 'clone',
            items: 'li',
            listType: 'ol',
            maxLevels: 1,
            opacity: .6,
            placeholder: 'placeholder',
            tolerance: 'pointer',
            toleranceElement: '> div',
            update: function () {
                save();
            }
        });

        function save(){

            $("#items").fadeTo("fast" , 0.5);

            var list = [];
            $('#items li').each(function(){
                list.push($(this).data('id'));
            });
            // console.log(list);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.productcategories.reorder', ['id' => $category->id]) }}",
                data: {
                    order: list
                },
                globalLoading: true,
                success : function (response){
                    console.log('success');
                    console.log(response);
                    toastr["success"](response);
                    $("#items").fadeTo("fast" ,1);
                },
                error : function (response){
                    console.log('error');
                    toastr["error"](response);
                    console.log(response);
                }
            });

        }

    </script>
@endsection
