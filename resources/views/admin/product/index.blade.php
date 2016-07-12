@extends('admin/master/index')
@section('content')
        <div class="box">
            <div class="box-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                            <table class="table table-bordered table-hover" id="products-table">
                                <thead>
                                <tr>
                                    <th>Product Id</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <!-- <th>Uploaded By</th> -->
                                    <!-- <th>Favorites</th> -->
                                    <!-- <th>Downloads</th> -->
                                    <!-- <th>Featured At</th> -->
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
        $(function() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! route('admin.products.data', ['type' => $type]) !!}',
                columns: [
                    { data: 'id', name: 'products.id'},
                    { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                    { data: 'title', name: 'products.title' },
                    // { data: 'fullname', name: 'fullname', },
                    // { data: 'favorites', name: 'favorites', searchable: false},
                    // { data: 'downloads', name: 'products.downloads' },
                    // { data: 'featured_at', name: 'products.featured_at' },
                    { data: 'created_at', name: 'products.created_at' },
                    { data: 'updated_at', name: 'products.updated_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
                "fnInitComplete": function () {
                    productApprove();
                    productDisapprove();
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