    @include('master/head')

    <body>

        @include('master/notices')

        <div class="container">

            @include('master/header')

            @yield('content')

            @include('master/footer')

        </div>

    </body>
</html>

@include('master/foot')