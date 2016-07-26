@extends('admin/master/index')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">{{ t('Create') }}</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                <table class="table table-bordered table-hover" id="products-table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Id</th>
                                        <th>{{ t('Title') }}</th>
                                        <th>{{ t('Created At') }}</th>
                                        <th>{{ t('Updated At') }}</th>
                                        <th>{{ t('User') }}</th>
                                        <th>{{ t('Profile') }}</th>
                                        <th>{{ t('Actions') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT', 'route' => 'admin.products.create', 'role' => 'form'])  !!}
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
                            <label for="title">{{ t('Title') }}</label>
                            {!! Form::text('title',null,['class'=>'form-control','placeholder'=>t('Title')])  !!}
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

    <script>
        $(function() {

            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                pagingType: 'full_numbers',
                search: {
                    caseInsensitive: true
                },
                order: [[ 2, "asc" ]],
                ajax: '{!! route('admin.products.data', ['type' => $type]) !!}',
                columns: [
                    { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                    { data: 'id', name: 'products.id'},
                    { data: 'title', name: 'products.title' },
                    { data: 'created_at', name: 'products.created_at' },
                    { data: 'updated_at', name: 'products.updated_at' },
                    { data: 'user', name: 'user' },
                    { data: 'profile', name: 'profile' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
                fnInitComplete: function () {
                    productApprove();
                    productDisapprove();
                    $('div.dataTables_filter input').addClass('form-control');
                    $('div.dataTables_length select').addClass('form-control');
                },
                language: {
                    processing: '<i class="fa fa-cog fa-spin fa-fw loading fa-2x"></i>',
                    sSearch: '{{ t("Search") }} ',
                    oPaginate: {
                        sFirst: '{{ t("First") }}',
                        sLast: '{{ t("Last") }}',
                        sNext: '{{ t("Next") }}',
                        sPrevious: '{{ t("Previous") }}'
                    },
                    sEmptyTable: '{{ t("Empty") }}',
                    sZeroRecords: '{{ t("Empty") }}',
                    sLengthMenu: '{{ t("Showing") }} _MENU_ {{ t("records") }}'
                }
            });

        });


        function productApprove() {
            $(".product-approve").on("click", function () {
                var c = $(this);
                var b = c.data('approve');
                $("a[data-disapprove='" + b + "']").toggle();
                $.ajax({
                    type: "POST", url: "<?=route('admin.products.approve')?>", data: 'id=' + b + '&approve=' + 1, success: function (a) {
                        $.when(c.fadeOut(300).promise()).done(function () {
                            if (c.hasClass("btn")) {
                                c.text(a).fadeIn();
                            } else {
                                c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                            }
                        })
                    }
                });
                return false;
            })
        }

        function productDisapprove() {
            $(".product-disapprove").on("click", function () {
                var c = $(this);
                var b = c.data('disapprove');
                $("a[data-approve='" + b + "']").toggle();
                $.ajax({
                    type: "POST", url: "<?=route('admin.products.approve')?>", data: 'id=' + b + '&approve=' + 0, success: function (a) {
                        $.when(c.fadeOut(300).promise()).done(function () {
                            if (c.hasClass("btn")) {
                                c.text(a).fadeIn();
                            } else {
                                c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                            }
                        })
                    }
                });
                return false;
            });
        }



    </script>
@endsection