@extends('master/index')
@section('title')
    @if(Input::get('category')){{ $title }} {{ t('in category') }} {{ getCategoryName(Input::get('category')) }}@else{{ $title }}@endif
@endsection
@section('content')
    <h1 class="content-heading">{{ $title }} @if(Input::get('category')){{ t('in category') }} {{ getCategoryName(Input::get('category')) }}@endif</h1>
    @include('gallery/util-list')

    <div class="row">
        @foreach($products as $item)
            @if($item->approved_at)
                <div class="col-md-4 col-sm-4 gallery-display">
                    <figure>
                        <a href="{{ route('product', ['id' => $item->id, 'slug' => $item->slug]) }}">
                            <img data-original="{{ Resize::img($item->main_image, 'featuredProduct')  }}" alt="{{ str_limit($item->title,30) }}" class="display-image">
                        </a>
                        <a href="{{ route('product', ['id' => $item->id, 'slug' => $item->slug]) }}" class="figcaption">
                            <h3>{{ str_limit($item->title, 40) }}</h3>
                            <span>{{ str_limit($item->description, 80) }}</span>
                        </a>
                    </figure>
                </div>
            @endif
        @endforeach
    </div>

@endsection
