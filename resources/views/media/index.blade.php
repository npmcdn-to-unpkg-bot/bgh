@extends('master/index')



@section('sidebar')
sdsaaaddasa
@endsection


@section('content')

    <a class="breadcrum" href="{{ route('medias') }}/">{{ t('Media') }}</a><span>&nbsp;/&nbsp;</span>

	<div class="row">
        <h1>{{ $title }}</h1>
    </div>



@endsection

@section('extra-js')

    <script>
        (function() {

        })();
    </script>

@endsection