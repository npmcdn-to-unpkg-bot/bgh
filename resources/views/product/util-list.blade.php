<div class="util-list">
    <div class="btn-group">
        @if(Input::get('timeframe') && in_array(Input::get('timeframe'),['now','week','month','year']))
            <a href="#" data-toggle="dropdown"><i class="fa fa-clock-o fa-fw"></i> {{ t(ucfirst(Input::get('timeframe'))) }} <span class="caret"></span></a>
        @else
            <a href="#" data-toggle="dropdown"><i class="fa fa-clock-o fa-fw"></i> {{ t('Time Frame') }} <span class="caret"></span></a>
        @endif
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ Request::url() }}?{{ query_params(['timeframe' => 'now']) }}">{{ t('Now') }}</a></li>
            <li><a href="{{ Request::url() }}?{{ query_params(['timeframe' => 'week']) }}">{{ t('Week') }}</a></li>
            <li><a href="{{ Request::url() }}?{{ query_params(['timeframe' => 'month']) }}">{{ t('Month') }}</a></li>
            <li><a href="{{ Request::url() }}?{{ query_params(['timeframe' => 'year']) }}">{{ t('Year') }}</a></li>
        </ul>
    </div>
    <div class="btn-group">
        @if(Input::get('category'))
            <a href="#" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> {{ getCategoryName(Input::get('category')) }} <span class="caret"></span></a>
        @else
            <a href="#" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> {{ t('Category') }} <span class="caret"></span></a>
        @endif
        <ul class="dropdown-menu" role="menu">
            @foreach(productCategories() as $category)
                <li><a href="{{ Request::url() }}?{{ query_params(['category' => $category->slug]) }}">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>