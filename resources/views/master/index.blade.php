<!doctype html>
<html class="js csstransitions" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', e($title)) - {{ siteSettings('siteName') }}</title>
    @yield('meta', '<meta name="description" content="'.siteSettings('description').'">')
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <!-- TIMEZONE: <?=Config::get('app.timezone')?> -->
    <!-- LOCALE: <?=Config::get('app.locale')?> -->
    <!-- SERVERTIME: <?=date('m/d/Y h:i:s a', time())?> -->
    <!--[if IE 8]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    {!! HTML::style('static/css/style.css') !!}
    {!! HTML::style('static/css/bootstrap.css') !!}

    {!! HTML::style('static/css/normalize.css') !!}
    {!! HTML::style('static/css/demo.css') !!}
    {!! HTML::style('static/css/icons.css') !!}
    {!! HTML::style('static/css/component.css') !!}

    {!! HTML::script('static/js/modernizr.custom.js') !!}

    @yield('style')
</head>
<body>
@include('master/notices')

@yield('above-container')

<div class="container">

    <!-- Push Wrapper -->
    <div class="mp-pusher" id="mp-pusher">

        @include('master/header')

        <!-- mp-menu -->
        <nav id="mp-menu" class="mp-menu">
            <div class="mp-level">
                <h2 class="icon icon-world">{{ t('Products') }}</h2>
                <ul>
                    <?php

                    $curDepth = 0;
                    $counter = 0;
                    foreach (productCategories() as $category){

                        if ($category->depth == $curDepth){
                            if ($counter > 0) echo "</div></li>";
                        }
                        elseif ($category->depth > $curDepth){
                            echo "<ul>";
                            $curDepth = $category->depth;
                        }
                        elseif ($category->depth < $curDepth){
                            echo str_repeat("</div></li></ul>", $curDepth - $category->depth), "</div></li>";
                            $curDepth = $category->depth;
                        }

                        ?>
                        <li class="icon icon-arrow-left">
                            <a class="icon icon-display" href="#">{{ $category->name }}</a>
                            <div class="mp-level">
                                <h2 class="icon icon-display"><a href="{{ $category->getLink() }}" class="mp-title">{{ $category->name }}</a></h2>
                                <a class="mp-back" href="#">{{ t('back') }}</a>
                        <?php

                        $counter++;
                    }

                    echo str_repeat("</div></li></ul>", $curDepth), "</li>";

                    ?>
                </ul>
            </div>
        </nav>
        <!-- /mp-menu -->



        <div class="scroller"><!-- this is for emulating position fixed of the nav -->
            <div class="scroller-inner">

                <div id="main_content" class="content clearfix">

                    <div class="row">
                        @yield('custom')


                        <div class="col-md-3">
                            @yield('sidebar')
                        </div>


                        <div class="col-md-9">
                            @yield('content')
                        </div>

                    </div>

                    @include('master/footer')
                </div>
            </div><!-- /scroller-inner -->
        </div><!-- /scroller -->

    </div><!-- /pusher -->




</div><!-- /container -->


{!! HTML::script('static/js/classie.js') !!}
{!! HTML::script('static/js/mlpushmenu.js') !!}

{!! HTML::script('static/js/main.js') !!}
{!! HTML::script('static/js/custom.min.js') !!}
@yield('extrafooter')
</body>
</html>
@yield('extra-js')

<script>

    new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );

    // var tri =document.getElementById("trigger_alias");
    // tri.onclick = function(){
    //     console.log("hello");
    //     ori = document.getElementById( 'trigger' );
    //     ori.click();
    //     return false;
    // };

</script>