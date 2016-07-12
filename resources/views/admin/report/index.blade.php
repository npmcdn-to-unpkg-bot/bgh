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
                        <table class="table table-bordered table-hover" id="images-table">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Reported Id</th>
                                <th>Reported By</th>
                                <th>Details</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Read Report</th>
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
            $('#images-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{!! route('admin.reports.data') !!}',
                columns: [
                    { data: 'type', name: 'type'},
                    { data: 'report', name: 'report' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'description', name: 'description', },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'read_report', name: 'read_report' },
                ]
            });
        });
    </script>
@endsection