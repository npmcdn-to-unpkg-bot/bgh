@extends('admin/master')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">
            <small><i class="fa fa-picture-o"></i></small>
            {{ $title }}
            <small>( {{ $products->count() }} products )</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filter Table</label>
                    <input class="form-control" id="filter" placeholder="Filter Table">
                </div>
            </div>
            <form method="GET">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Direction</label>
                        {{ Form::select('direction', array('asc' => 'Asc', 'desc' => 'Desc'), Request::get('direction'),array('class'=>'form-control')) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <input type="submit" class="btn btn-info form-control" value="Filter Data"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover footable toggle-medium" data-filter="#filter">
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Uploaded By</th>
            <th>Add/Remove</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
        @if($product and $product->user)
        <tr>
            <td><a href="{{ route('product', ['id' => $product->id, 'slug' => $product->slug]) }}"><img src="{{ reSizeProduct($product,69,69,'zoomCrop') }}"/></a></td>

            <td><span data-toggle="tooltip" data-placement="right" data-original-title="{{ $product->title }}">{{ str_limit($product->title,10) }}</span></td>
            <td><a data-toggle="tooltip" data-placement="right" data-original-title="{{ $product->user->fullname }}" href="{{ url('user/'.$product->user->username) }}">{{ str_limit($product->user->fullname,15) }}</a></td>
            <td>
                <a id="{{ $product->id }}" data-toggle="tooltip" data-placement="top" data-original-title="Approve" class="btn btn-success admin-product-approve approve-{{ $product->id }}" href="#">
                    <i class="fa fa-check"></i>
                </a>
                <a id="{{ $product->id }}" data-toggle="tooltip" data-placement="top" data-original-title="Disapprove" class="btn btn-danger admin-product-disapprove disapprove-{{ $product->id }}" href="#">
                    <i class="fa fa-times"></i>
                </a></td>
            <td><abbr class="timeago" title="{{ date(DATE_ISO8601,strtotime($product->created_at)) }}">{{ $product->created_at->toISO8601String() }}</abbr></td>
            <td>
                <a class="btn btn-success" href="{{ route('product', ['id' => $product->id, 'slug' => $product->slug]) }}">
                    <i class="glyphicon glyphicon-zoom-in"></i>
                </a>
                <a class="btn btn-info" href="{{ url('admin/product/'.$product->id.'/edit') }}">
                    <i class="glyphicon glyphicon-edit"></i>
                </a></td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
</div>
{{ $products->links() }}
<!-- /.table-responsive -->
@endsection