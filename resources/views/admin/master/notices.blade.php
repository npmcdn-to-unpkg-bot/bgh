@if(Session::has('flashSuccess'))
    <script>
        $(document).ready(function() {
            toastr["success"]('{{ Session::get('flashSuccess') }}');
        });
    </script>
@endif

@if(Session::has('flashError'))
    <script>
        $(document).ready(function() {
            toastr["error"]('{{ Session::get('flashError') }}');
        });
    </script>
@endif

@if(Session::has('errors'))
    <script>
        $(document).ready(function() {
            toastr["error"]('{{ Session::get('errors')->first() }}');
        });
    </script>
@endif