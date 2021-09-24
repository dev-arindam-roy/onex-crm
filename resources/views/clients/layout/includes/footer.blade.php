<div id="app-tostr"></div>
<script src="{{ asset(config('onex.client_assets_path') . '/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset(config('onex.client_assets_path') . '/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('onex.client_assets_path') . '/plugins/pace-progress/pace.min.js') }}"></script>
<script src="{{ asset(config('onex.client_assets_path') . '/dist/js/adminlte.min.js') }}"></script>
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
