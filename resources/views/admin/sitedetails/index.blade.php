@extends('admin/master/index')

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-maroon-active"><i class="ion ion-cube"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Products</span>
                    <span class="info-box-number">{{ \App\Models\Product::approved()->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-chatboxes-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Comments</span>
                    <span class="info-box-number">{{ \App\Models\Comment::count() }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Users</span>
                    <span class="info-box-number">{{ \App\Models\User::count() }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>

         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Visits</span>
              <span class="info-box-number">2,000</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>



    </div>
    <div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Daily Signup Chart</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="col-md-12 chart">
                    <div id="signup-container"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Site Details ( Quick Look )</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <div id="donut-chart"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('extra-js')
    <script>
        Morris.Donut({
            element: 'donut-chart',
            resize: true,
            colors: ["#3c8dbc", "#f56954", "#00a65a"],
            data: [
                {label: "Products", value: {{ \App\Models\Product::count() }} },
                {label: "Users", value: {{ \App\Models\User::count() }}},
                {label: "Comments", value: {{ \App\Models\Comment::count() }} },
                {label: "Reply", value: {{ \App\Models\Reply::count() }} },
            ]
        });

        // var data = JSON.parse('{!! $signup !!}');
        // new Morris.Area({
        //     element: 'signup-container',
        //     data: data,
        //     xkey: 'date',
        //     ykeys: ['value'],
        //     labels: ['Users'],
        //     lineColors: ['#3c8dbc'],
        //     hideHover: 'auto',
        //     parseTime: false,
        //     resize: true
        // });

        // var newsdata = JSON.parse('{!! $products !!}');
        // new Morris.Area({
        //     element: 'products-container',
        //     data: newsdata,
        //     xkey: 'date',
        //     ykeys: ['value'],
        //     labels: ['Products'],
        //     lineColors: ['#3c8dbc'],
        //     hideHover: 'auto',
        //     parseTime: false,
        //     resize: true
        // });
    </script>
@endsection
