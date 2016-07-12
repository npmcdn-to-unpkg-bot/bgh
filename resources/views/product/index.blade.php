@extends('master/index')



@section('sidebar')
sdsaaaddasa
@endsection


@section('content')

    <a class="breadcrum" href="{{ route('products') }}/">{{ t('Products') }}</a><span>&nbsp;/&nbsp;</span>

	<div class="row">
        <h1>{{ $title }}</h1>
    </div>

    <div class="row">
        <div class="col-md-5 col-sm-5">
            <ul>
                <?php

                $curDepth = 0;
                $counter = 0;
                foreach ($categories as $category){

                    if ($category->depth == $curDepth){
                        if ($counter > 0) echo "</li>";
                    }
                    elseif ($category->depth > $curDepth){
                        echo "<ul>";
                        $curDepth = $category->depth;
                    }
                    elseif ($category->depth < $curDepth){
                        echo str_repeat("</li></ul>", $curDepth - $category->depth), "</li>";
                        $curDepth = $category->depth;
                    }

                    ?>
                    <li id='category_{{ $category->id }}' data-id='{{ $category->id }}'>
                    <a href="{{ $category->getLink() }}">{{ $category->name }}</a>
                    <?php

                    $counter++;
                }

                echo str_repeat("</li></ol>", $curDepth), "</li>";

                ?>
            </ul>
        </div>
    </div>

@endsection

@section('extra-js')

    <script>
        (function() {

        })();
    </script>

@endsection