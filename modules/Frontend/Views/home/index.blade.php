@extends('Frontend::layouts.index')
@section('body-client')
<script type="text/javascript" src="{{ URL::asset('dist/js/backend/client/JS_Home.js') }}"></script>
<script src='../assets/js/jquery.js'></script>
<div id="table-container"></div>
<script type="text/javascript">
    var baseUrl = "{{ url('') }}";
    var JS_Home = new JS_Home(baseUrl);
    $(document).ready(function($) {
        JS_Home.loadIndex(baseUrl);
    })
</script>
@endsection
