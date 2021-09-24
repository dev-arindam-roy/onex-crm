<ul class="navbar-nav" id="navbar-header">
  <li class="nav-item">
    <h4 class="mb-0 d-inline-block font-weight-bold">
      ONEX
    </h4>
    <h3 class="text-primary d-inline-block font-weight-bold">
      <span class="logo-span"></span> Master
    </h3>
  </li>
  <li class="nav-item">
    <!-- <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> -->
    <button class="nav-link menu opened" data-widget="pushmenu" onclick="this.classList.toggle('opened');this.setAttribute('aria-expanded', this.classList.contains('opened'))" aria-label="Main Menu">
      <svg width="28" height="28" viewBox="0 0 100 100">
      <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
      <path class="line line2" d="M 20,50 H 80" />
      <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
      </svg>
  </button>
  </li>
</ul>

<ul class="navbar-nav ml-auto" id="onexMasterHeaderNav">
  @include('onex.loading')
  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-user text-primary text-lg"></i>
      {{ Auth::guard('admin')->user()->first_name }}
    </a>
    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
      <a href="{{ route('administrator.account.myprofile') }}" class="dropdown-item">
        <i class="fas fa-user text-primary"></i> My Profile
      </a>
      <a href="{{ route('administrator.account.changeemail') }}" class="dropdown-item">
        <i class="fas fa-envelope text-primary"></i> Change Email
      </a>
      <a href="{{ route('administrator.account.changepassword') }}" class="dropdown-item">
        <i class="fas fa-key text-primary"></i> Change Password
      </a>
      <div class="dropdown-divider"></div>
      <a href="javascript:void(0);" class="dropdown-item" @click="logoutSubmit">
        <i class="fas fa-sign-out-alt text-primary"></i>
        Logout
      </a>
    </div>
  </li>
</ul>

@push('page_js')
<script>
let headerNavVue = new Vue({
  el: '#onexMasterHeaderNav',
  data() {
    return {
      isLoading: true,
      userEmail: "{!! Auth::guard('admin')->user()->email_id !!}"
    }
  },
  watch: {
        
  },
  computed: {
    
  },
  methods: {
    async logoutSubmit() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('administrator.account.logout') }}";
      const process = await axios({
          method: 'post',
          url: url,
          data: {
          email_id: _this.userEmail
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        if (response.data.status === 200) {
            _this.isLoading = false;
            _this.$toastr.removeByType("error");
            _this.$toastr.s("You have successfully sign out from your account.", "Logout Successfull!");
            Swal.fire({
                icon: 'success',
                title: 'Logout Successfull!',
                html: 'Please wait...',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            setTimeout(function () { 
                window.location.href = "{{ route('administrator.auth.signin') }}";
            }, 3000);
        }
      }).catch(function (error) {
        _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
        setTimeout(function () { 
            location.reload();
        }, 3000);
      });
    },
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush
