<div class="clearfix">
    <h3 class="content-heading">{{ t('Share This') }}</h3>
    @include('master/share')
</div>


@if($media->tags)
    <h3 class="content-heading">{{ t('Tags') }}</h3>
    <ul class="list-inline taglist">
        @foreach(explode(',',$media->tags) as $tag)
            <li><a href="{{ route('tags',$tag) }}" class="tag"><span class="label label-info">{{ $tag }}</span></a></li>
        @endforeach
    </ul>
@endif
