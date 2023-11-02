<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/resources/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('/resources/img/favicon.png') }}">
    <title>
        Quản trị hệ thống CMS
    </title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('/resources/assets/fontawesome-free-5.15.4-web/css/all.min.css') }}">
    <!-- Bootstrap 5.2.3 + Soft UI -->
    <link id="pagestyle" href="{{ asset('/resources/assets/soft-ui-dashboard/css/soft-ui-dashboard.css?v=1.0.7') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/resources/css/assets/jquery-confirm.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/resources/css/assets/jquery-contextmenu/2.9.2/jquery.contextMenu.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/resources/css/app.css') }}">

    <!-- Core JS Files -->
    <script src="{{ asset('/resources/js/assets/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/jquery-contextmenu/2.9.2/jquery.contextMenu.min.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/popper.min.js') }}"></script>
    <script src="{{ asset('/resources/assets/fontawesome-free-5.15.4-web/js/all.min.js') }}"></script>
    <script src="{{ asset('/resources/assets/bootstrap-5.2.3-dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('/resources/js/assets/nanobar.min.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/EfyLibrary.js') }}"></script>
    <script type="text/jscript"  src="{{ asset('/resources/js/assets/CoreTable.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/TableXml.js') }}"></script>
    <script src="{{ asset('/resources/js/assets/ckeditor/ckeditor.js') }}"></script>

    @yield('style')

</head>

<body id="page-top" class="g-sidenav-show bg-gray-100">
    <span hidden data-class="bg-white"></span>
    <!-- Thông báo -->
    <div id="message-alert">
        <h4>
            <strong>
                <i id="message-icon"></i>
                <span id="message-label"></span>
            </strong>
        </h4>
        <span id="message-infor"></span>
    </div>

    <!-- loading -->
    <div id="loading">
        <img src="{{ asset('/resources/img/loading/96x96.gif') }}" alt="loading">
    </div>

    <!-- Sidebar -->
    @include('system.layouts.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="margin-left: 15rem;">
        <!-- Navbar -->
        @include('system.layouts.navbar')

        <div class="container-fluid py-2" style="min-height: 100vh; padding-right: 0.5rem;">
            <div class="card h-100">
                <div class="card-body p-3">
                    <!-- Content -->
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            @include('system.layouts.footer')
        </div>
    </main>

    <script src="{{ asset('/resources/assets/soft-ui-dashboard/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/resources/assets/soft-ui-dashboard/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/resources/assets/soft-ui-dashboard/js/plugins/chartjs.min.js') }}"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('/resources/assets/soft-ui-dashboard/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), {
                damping: '0.5'
            });
        }

        // Menu click event
        $(document).ready(function() {
            $('.menu li:has(ul)').click(function(e) {
                if (
                    $(e.target).parent().parent().hasClass('navbar-nav') &&
                    $(e.target).parent().parent().is(':not(.nav-item)')
                ) {
                    $('.menu li ul').slideUp();
                    if (!$(this).children('ul').is(':visible')) {
                        $(this).children('ul').slideDown();
                    }
                }
            });
        });
    </script>

    @yield('script')

</body>

</html>
