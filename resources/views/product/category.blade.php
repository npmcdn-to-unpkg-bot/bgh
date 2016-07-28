@extends('master/index')


@section('sidebar')

    <ul>
        <?php

        $curDepth = 0;
        $counter = 0;
        foreach ($categories as $c){

            if ($c->depth == $curDepth){
                if ($counter > 0) echo "</li>";
            }
            elseif ($c->depth > $curDepth){
                echo "<ul>";
                $curDepth = $c->depth;
            }
            elseif ($c->depth < $curDepth){
                echo str_repeat("</li></ul>", $curDepth - $c->depth), "</li>";
                $curDepth = $c->depth;
            }

            ?>
            <li id='category_{{ $c->id }}' data-id='{{ $c->id }}'>
            <a href="{{ $c->getLink() }}">{{ $c->name }}</a>
            <?php

            $counter++;
        }

        echo str_repeat("</li></ol>", $curDepth), "</li>";

        ?>
    </ul>

@endsection


@section('content')

	<div class="row">
          <a class="breadcrum" href="{{ route('products') }}/">{{ t('Products') }}</a><span>&nbsp;/&nbsp;</span>
        @foreach($breadcrum as $a)
            <a class="breadcrum" href="{{ route('products') }}/{{ $a->link }}">{{ str_limit($a->name, 40) }}</a><span>&nbsp;/&nbsp;</span>
        @endforeach
        <h1>{{ $category->name }}</h1>
    </div>

    <div class="row">
        <ul class="product-list">
        @foreach($items as $item)
            <li>
                <a href="{{ $item->getLink()->url }}" target="{{ $item->getLink()->target }}" id="{{ $item->slug }}">
                    <img class="display-image image" data-original="{{ Resize::img($item->main_image, 'featuredProduct')  }}" alt="{{ $item->title }}">
                    <h3 class="title">{{ str_limit($item->title,80) }}</h3>
                </a>
            </li>
        @endforeach
        </ul>
    </div>

@endsection


@section('extra-js')

    <script>
        (function() {

        })();
    </script>
@endsection