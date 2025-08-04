<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{config('app.name','PBMS')}} | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
  <style>
    .card-title {
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
  @yield('style')
</head>

<body class="control-sidebar-slide-open layout-navbar-fixed layout-fixed layout-footer-fixed">
  <div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{asset('backend/dist/img/short_logo.png')}}" alt="AdminLTELogo">
    </div>
    @include("backend.admin.layouts.partials._topbar")

    @include("backend.admin.layouts.partials._sidebar")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->


      @yield('content')

    </div>
    @include('backend.admin.layouts.partials._footer')

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  {{-- <script src="{{asset('backend/dist/js/demo.js')}}"></script> --}}
  <!-- Page specific script -->
  <script>
    $(document).on('select2:open', () => {
      let searchField = document.querySelector('.select2-container--open .select2-search__field');
      if (searchField) {
        searchField.focus();
      }
    });
    $(document).ready(function() {
      $('input').attr('autocomplete', 'off');
      var a = $('input[required]');
      // debugger;
      a.toArray().forEach(function(item) {
        // debugger;
        item.parentNode.childNodes.forEach(function(child) {
          // debugger;
          if (child.tagName === 'LABEL') {
            child.innerHTML = child.innerHTML + ' <span class="text-danger text-bold">*</span>';
          }
        });
      });





      
      var a = $('select[required]');
      // debugger;
      a.toArray().forEach(function(item) {
        //  debugger;
        item.parentNode.childNodes.forEach(function(child) {
          // debugger;
          if (child.tagName === 'LABEL') {
            child.innerHTML = child.innerHTML + ' <span class="text-danger text-bold">*</span>';
          }
        });
      });
    });
  </script>

  @yield('script')

</body>

</html>