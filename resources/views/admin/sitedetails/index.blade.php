@extends('admin/master/index')

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-maroon-active"><i class="ion ion-cube"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{t('Products')}}</span>
                    <span class="info-box-number">{{ \App\Models\Product::approved()->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-file"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{t('Pages')}}</span>
                    <span class="info-box-number">{{ \App\Models\Page::count() }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{t('Users')}}</span>
                    <span class="info-box-number">{{ \App\Models\User::count() }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>

         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">{{t('Visits')}}</span>
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
                <h3 class="box-title">Algún grafiquito copado</h3>
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
                <h3 class="box-title">Otro grafico</h3>
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
                {label: "{{t('Products')}}", value: {{ \App\Models\Product::count() }} },
                {label: "{{t('Users')}}", value: {{ \App\Models\User::count() }}},
                {label: "{{t('Pages')}}", value: {{ \App\Models\Page::count() }} },
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
