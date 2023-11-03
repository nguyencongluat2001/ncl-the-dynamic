<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <base href="{{ asset('') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Shopping</title>
  <link rel="icon" href="../img/Fevicon.png" type="image/png">
  <link rel="stylesheet" href="../vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../vendors/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../vendors/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="../vendors/nice-select/nice-select.css">
  <link rel="stylesheet" href="../vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="../vendors/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="../css/assets/sweetalert2.min.css">

  <script src="../js/assets/jquery-3.7.0.min.js"></script>
  <script src="../js/assets/popper.min.js"></script>
  <script src="../assets/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  <script src="../assets/fontawesome-free-5.15.4-web/js/all.min.js"></script>
  <script src="../js/assets/chosen/chosen.jquery.min.js"></script>
  <script src="../js/assets/sweetalert2.min.js"></script>
  <script src="../js/assets/NclLibrary.js"></script>

  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/templatemo.css">

  <script type="text/javascript">
        // Page has finished loading, hide the loading container
        window.onload = function() {
            document.getElementById("loading").style.display = "none";
        };
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery(document).ajaxSend(function() {
            NclLib.showmainloadding();
        });
        jQuery(document).ajaxStop(function() {
            NclLib.successLoadImage();
        });
    </script>

    @yield('style')
</head>
<body>
  <!--================ Start Header Menu Area =================-->
	<header class="header_area">
    <div class="main_menu">
       @include('Frontend::layouts.navbar')
    </div>
  </header>
	<!--================ End Header Menu Area =================-->
  <main class="site-main">
    <!--================ Hero banner start =================-->
    @yield('body-client')
  </main>

  @include('Frontend::layouts.footer')

  <script src="../vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="../vendors/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="../vendors/skrollr.min.js"></script>
  <script src="../vendors/owl-carousel/owl.carousel.min.js"></script>
  <script src="../vendors/nice-select/jquery.nice-select.min.js"></script>
  <script src="../vendors/jquery.ajaxchimp.min.js"></script>
  <script src="../vendors/mail-script.js"></script>
  <script src="../js/main.js"></script>

  <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    @yield('script')
</body>
</html>