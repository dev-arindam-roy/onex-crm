<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('onex.page_title') }} @yield('page_title')</title>
  <link rel="icon" type="image/png" href="{{ asset(config('onex.assets_path') . '/images/onex24.png') }}" />
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/dist/css/sanspro-font.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-loading/vue-loading.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.backend_assets_path') . '/css/onex.css') }}">
  @stack('page_css')
</head>
<body class="hold-transition register-page">
  <div id="app-tostr"></div>
  @yield('page_content')
  <script src="{{ asset(config('onex.client_assets_path') . '/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset(config('onex.client_assets_path') . '/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset(config('onex.client_assets_path') . '/dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuejs/vue.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuelidate/vuelidate.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuelidate/validators.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vue-toastr/vue-toastr.umd.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vue-loading/vue-loading-overlay.js') }}"></script>
  <script>
  let vueTostr = new Vue({
    el: '#app-tostr',
    mounted() {
      this.$toastr.defaultTimeout = 10000;
      this.$toastr.defaultClassNames = ["animated", "zoomInUp"];
      this.$toastr.defaultPosition = "toast-top-right";
      this.$toastr.defaultStyle = { "margin-top": "60px" };
      @if(session()->has('msg') && session()->has('msg_class'))
        @if(session()->get('msg_class') == 'alert alert-danger')
          this.$toastr.e("{!! session()->get('msg') !!}", "{{ session()->get('msg_title') }}");
        @endif

        @if(session()->get('msg_class') == 'alert alert-success')
          this.$toastr.s("{!! session()->get('msg') !!}", "{{ session()->get('msg_title') }}");
        @endif
      @endif
    }
  });
  </script>
  <script src="{{ asset(config('onex.public_path') . '/js/app.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/script.js') }}"></script>
  @stack('page_js')
</body>
</html>
