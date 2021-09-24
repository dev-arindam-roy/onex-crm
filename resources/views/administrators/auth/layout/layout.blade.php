<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('onex.page_title') }} @yield('page_title')</title>
  <link rel="icon" type="image/png" href="{{ asset(config('onex.assets_path') . '/images/onex24.png') }}" />
  <link rel="stylesheet" href="{{ asset(config('onex.administrator_assets_path') . '/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.administrator_assets_path') . '/dist/css/fonts/open-sans.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.administrator_assets_path') . '/plugins/bootstrap-5.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.administrator_assets_path') . '/dist/css/suitex.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.administrator_assets_path') . '/dist/css/suitex.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-loading/vue-loading.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
  @stack('page_css')
</head>

<body class="hold-transition login-page bg-white">
  <div id="app-tostr"></div>
  <div class="login-box">
    <div class="login-logo">
      <a href="{{ route('administrator.auth.signin') }}" class="h1">
        <img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 40px;">
        <h2 class="mb-0 d-inline-block font-weight-bold">
          ONEX
        </h2>
        <h1 class="text-primary d-inline-block span-logo-text">
          <span class="logo-span"></span> Master 
        </h1>
      </a>
    </div>
    <div class="card">
      @yield('page_content')
    </div>
  </div>
  <script src="{{ asset(config('onex.administrator_assets_path') . '/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset(config('onex.administrator_assets_path') . '/plugins/bootstrap-5-bundle.min.js') }}"></script>
  <script src="{{ asset(config('onex.administrator_assets_path') . '/dist/js/suitex.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuejs/vue.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuelidate/vuelidate.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vuelidate/validators.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vue-toastr/vue-toastr.umd.min.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vue-loading/vue-loading-overlay.js') }}"></script>
  <script src="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.all.min.js') }}"></script>
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