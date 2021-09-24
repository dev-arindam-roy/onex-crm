@extends('clients.layout.auth')

@section('page_title', ' | Reset Password')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="clientResetPassword" v-cloak>

  @include('onex.loading')
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Reset Password</p>
      @include('onex.validation_list')

      <!-- form -->
      <form name="clientResetPwdFrm" id="clientResetPwdFrm" @submit.prevent="resetPasswordSubmit" method="POST">
        
        <div class="form-group">
            <div class="input-group">
                <input :type="passwordOrText" 
                v-model.trim="password" 
                name="password" 
                id="password" 
                class="form-control" 
                v-bind:class="{ 'is-invalid': $v.password.$error, 'is-valid': !$v.password.$invalid && !$v.password.$error  }" 
                maxlength="20"
                placeholder="Password">
                <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock navy"></span>
                </div>
                </div>
            </div>
            <div class="text-danger ofs-14" v-if="!$v.password.required && $v.password.$error">Please enter password.</div>
            <div class="text-danger ofs-14" v-if="!$v.password.regxPassword && $v.password.required && $v.password.$error">Please enter a strong password.</div>
            <div class="text-danger ofs-14" v-if="!$v.password.minLength && $v.password.regxPassword && $v.password.required && $v.password.$error">Minimum 8 characters required.</div>
        </div>

        <div class="form-group">
            <div class="input-group">
              <input :type="passwordOrText" 
                v-model.trim="confirmPassword" 
                name="confirm_password" 
                id="confirm_password" 
                class="form-control" 
                v-bind:class="{ 'is-invalid': $v.confirmPassword.$error, 'is-valid': !$v.confirmPassword.$invalid && !$v.confirmPassword.$error  }" 
                maxlength="20"
                placeholder="Confirm Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock navy"></span>
                </div>
              </div>
            </div>
            <div class="text-danger ofs-14" v-if="!$v.confirmPassword.required && $v.confirmPassword.$error">Please enter password.</div>
            <div class="text-danger ofs-14" v-if="!$v.confirmPassword.sameAs && $v.confirmPassword.required && $v.confirmPassword.$error">Confirm password not match.</div>
        </div>

          <div class="row">
            <div class="col-12 mb-3">
              <button type="button" class="btn btn-block btn-outline-navy btn-xs" @click="showHidePassword">@{{showHideText}}</button>
            </div>
          </div>
        

        <div class="row">
          <!-- /.col -->
            <div class="col-12">
                <button type="submit" class="btn bg-navy btn-block" :disabled="!isDisabled">Reset Password</button>
            </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="{{ route('client.auth.signin') }}">Sign in ?</a>
      </div>
      <div class="text-center">
        <a href="{{ route('client.auth.signup') }}">Create Account ?</a>
      </div>
    
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
@endsection

@push('page_js')
<script src="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
let pageVue = new Vue({
  el: '#clientResetPassword',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      password: "",
      confirmPassword: "",
      showHideText: 'Show Password',
      passwordOrText: 'password'
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.password != '' && this.confirmPassword != '';
    }
  },
  validations: {
    password: {
      required,
      regxPassword,
      minLength: minLength(8),
      maxLength: maxLength(20)
    },
    confirmPassword: {
      required,
      sameAs: sameAs('password'),
      maxLength: maxLength(20)
    }
  },
  methods: {
    resetPasswordSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.resetPasswordSubmitProcess();
      }
    },
    showHidePassword() {
      if (this.passwordOrText == 'password') {
        this.passwordOrText = 'text';
        this.showHideText = 'Hide Passwords';
      } else {
        this.passwordOrText = 'password';
        this.showHideText = 'Show Passwords';
      }
    },
    async resetPasswordSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('client.auth.forgot.password.reset.save', array('token' => $reset_token)) }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          password: _this.password,
          confirm_password: _this.confirmPassword
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        if (response.data.status === 200) {
          if (response.data.body.content.user != undefined) {
            _this.isLoading = false;
            _this.password = '';
            _this.confirmPassword = '';
            _this.$v.$reset();
            _this.$toastr.removeByType("error");
            _this.$toastr.s("Your account password has been reset successfully, thankyou.", "Reset Password!");
            Swal.fire({
                icon: 'success',
                title: 'Password Reset',
                html: 'Your new password has been saved successfully.<br/>Thankyou',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            setTimeout(function () { 
              window.location.href = "{{ route('client.auth.signin') }}";
            }, 3000);
          }
        }
      }).catch(function (error) {
        if (error.response.status == 422) {
          _this.isLoading = false;
          _this.validationErrors = error.response.data.body.content.validationErrors;
        } else if (error.response.status == 404) {
            _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
            setTimeout(function () { 
                window.location.href = "{{ route('client.auth.forgot.password') }}";
            }, 3000);
        } else {
          _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
          setTimeout(function () { 
            location.reload();
          }, 3000);
        }
      });
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush
