@include('master/head')
<body>

    @include('master/notices')


    {!! HTML::style('static/css/demo.css') !!}
    {!! HTML::style('static/css/icons.css') !!}
    {!! HTML::style('static/css/component.css') !!}


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

            <div class="scroller">
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
                </div>
            </div>

        </div>

    </div><!-- /container -->


    {!! HTML::script('static/js/classie.js') !!}
    {!! HTML::script('static/js/mlpushmenu.js') !!}

    {!! HTML::script('static/js/main.js') !!}
    {!! HTML::script('static/js/custom.min.js') !!}

</body>
</html>

@yield('extra-js')

<script>

    new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );

</script>