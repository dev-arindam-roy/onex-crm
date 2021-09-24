<li class="nav-item dropdown" id="userProfileOption" v-cloak>
    @include('onex.loading')
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user fa-2x"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
        <!-- Message Start -->
        <div class="media">
            <img src="{{ asset(config('onex.client_assets_path') . '/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
            <h3 class="dropdown-item-title">
                Brad Diesel
            </h3>
            <p class="text-sm">Designation|Role</p>
            </div>
        </div>
        <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> My Profile
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-portrait mr-2"></i> Profile Image
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-id-card mr-2"></i> Download Onex Card
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-mobile-alt mr-2"></i> Verify Mobile
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-key mr-2"></i> Change Password
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Change Email-Id
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-at mr-2"></i> Change Username
        </a>
        <div class="dropdown-divider"></div>
        <a href="javascript:void(0);" class="dropdown-item" @click="logoutSubmit">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</li>

@push('page_js')
<script>
let pageVue = new Vue({
  el: '#userProfileOption',
  data() {
    return {
      isLoading: true,
      userEmail: "{!! Auth::user()->email_id !!}"
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
      var url = "{{ route('client.account.logout') }}";
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
                window.location.href = "{{ route('client.auth.signin') }}";
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