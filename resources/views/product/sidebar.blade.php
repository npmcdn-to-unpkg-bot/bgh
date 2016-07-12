<div class="clearfix">
    <h3 class="content-heading">{{ t('Share This') }}</h3>
    @include('master/share')
</div>


@if($product->tags)
    <h3 class="content-heading">{{ t('Tags') }}</h3>
    <ul class="list-inline taglist">
        @foreach(explode(',',$product->tags) as $tag)
            <li><a href="{{ route('tags',$tag) }}" class="tag"><span class="label label-info">{{ $tag }}</span></a></li>
        @endforeach
    </ul>
@endif

@if($product->categories)
    <h3 class="content-heading">{{ t('Categories') }}</h3>
    <ul class="list-inline taglist">
        @foreach($product->categories as $cat)
            <li><a href="{{ route('products') }}/{{ $cat->link }}" class="category"><span class="label label-info">{{ $cat->name }}</span></a></li>
        @endforeach
    </ul>
@endif
