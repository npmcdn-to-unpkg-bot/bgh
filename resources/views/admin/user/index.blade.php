@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                        <table class="table table-bordered table-striped table-hover" id="images-table">
                            <thead>
                            <tr>
                                <th>User Id</th>
                                <th>Avatar</th>
                                <th>Username</th>
                                <th>FullName</th>
                                <th>Email</th>
                                <th>Products</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
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
@endsection

@section('extra-js')
    <script>
        $(function () {




            @if(env('APP_DEBUG') == false)$.fn.dataTable.ext.errMode = 'none';@endif
            $('#images-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! route('admin.users.data', ['type' => $type]) !!}',
                columns: [
                    {data: 'id', name: 'users.id'},
                    {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
                    {data: 'username', name: 'username',},
                    {data: 'fullname', name: 'fullname',},
                    {data: 'email', name: 'users.email',},
                    {data: 'products', name: 'products', searchable: false},
                    {data: 'created_at', name: 'users.created_at'},
                    {data: 'updated_at', name: 'users.updated_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                fnInitComplete: function () {
                    userApprove();
                    userDisapprove();
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
                    sLengthMenu: '{{ t("Showing") }} _MENU_ {{ t("records") }}',
                    info: '{{ t("Showing") }} {{ t("page") }} _PAGE_ {{ t("of") }} _PAGES_',
                    infoEmpty: '{{ t("Empty") }}',
                }
            });
        });
    </script>
@endsection