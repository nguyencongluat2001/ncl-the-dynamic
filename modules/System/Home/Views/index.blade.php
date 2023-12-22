@extends('system.layouts_Backend.index')
@section('body')

<!-- /.content  --> 
<script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_Home.js') }}"></script>

Trang quản trị hệ thống
<!-- Hien thi modal -->
<div class="modal fade" id="addSystem" role="dialog">
</div>
<!-- <script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var Js_System = new Js_System(baseUrl,'system');
    jQuery(document).ready(function($) {
        Js_System.loadIndex();
})
</script> -->
@endsection