<div class="container">
    <footer>
        <p class="pull-right"><a href="#">{{ t('Back to top') }}</a></p>

        <p>&copy; {{ date("Y") }} {{ siteSettings('siteName') }}&nbsp;&middot;&nbsp;
            <a href="{{ route('products') }}">{{ t('Products') }}</a>&nbsp;&middot;&nbsp;
            @include('master/language')
        </p>
    </footer>
</div>