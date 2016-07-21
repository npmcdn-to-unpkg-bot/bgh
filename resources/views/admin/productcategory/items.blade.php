@extends('admin/master/index')

@section('content')

    {!! Form::open(['url'=>'admin/productcategories/update']) !!}

        <div class="row">
            <div class="col-md-8">

                <div class="form-group">

                    <div >
                        <ol id="items" class='sortable ui-sortable'>
                            @foreach($items as $item)
                                <li data-id="{{ $item->id }}">
                                    <div>
                                        <img src="{{ Resize::img($item->main_image, 'sidebarProduct')  }}" alt="{{ $item->slug }}" class="display-image">
                                        <span>{{ $item->title }}</span>
                                        <button class="remove pull-right btn btn-default btn-sm"><i class="fa fa-close"></i></button>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                </div>

            </div>
            <div class="col-md-4">

                <div class="form-group">
                    {!! Form::label('products', t('Add')) !!}

                    {!! Form::select('products_list', [], null, ['class' => 'form-control', 'id' => 'products_list']) !!}
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <button type="button" class="btn btn-default" rel="close">{{ t('Close') }}</button>

            </div>

        </div>
        {!! Form::text('id',$category->id,['class'=>'hidden']) !!}

    {!! Form::close() !!}

@endsection

@section('extra-js')

    <script type="text/javascript">

        $(function () {

            $("[rel=close]").on('click',function () {
                location.href='{{ url('admin/productcategories/') }}';
            });



            $('#products_list').select2({
                placeholder: '{{ t('Search') }}',
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
                $("#items").append('<li data-id="' + obj[0].id + '"><div><img src="' + obj[0].image + '" alt="' + obj[0].slug + '" class="display-image"><span>' + obj[0].text + '</span><button class="remove pull-right btn btn-default btn-sm"><i class="fa fa-close"></i></button></div></li>');
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
                url: "{{ route('admin.productcategories.items', ['id' => $category->id]) }}",
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
