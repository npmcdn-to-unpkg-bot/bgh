
@if($page->tags)
    <h3 class="content-heading">{{ t('Tags') }}</h3>
    <ul class="list-inline taglist">
        @foreach(explode(',',$page->tags) as $tag)
            <li><a href="{{ route('tags',$tag) }}" class="tag"><span class="label label-info">{{ $tag }}</span></a></li>
        @endforeach
    </ul>
@endif