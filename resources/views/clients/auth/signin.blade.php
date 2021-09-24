@php
  $loginId = '';
  $loginPwd = '';
  $remember = false;
  if (isset($_COOKIE) && !empty($_COOKIE['onexClientLogID']) && !empty($_COOKIE['onexClientLogPwd'])) {
    $loginId = $_COOKIE['onexClientLogID'];
    $loginPwd = $_COOKIE['onexClientLogPwd'];
    $remember = true;
  }
@endphp

@extends('clients.layout.auth')

@section('page_title', ' | Signin')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="clientSignIn" v-cloak>
  
  @include('onex.loading')
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign In</p>
      @include('onex.validation_list')
      
      <!-- form -->
      <form name="clientSignInFrm" id="clientSignInFrm" @submit.prevent="signinSubmit" method="POST">
      
        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="loginID" 
              name="loginid" 
              id="loginid" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.loginID.$error, 'is-valid': !$v.loginID.$invalid && !$v.loginID.$error  }" 
              maxlength="30"
              placeholder="Email or Username or Mobile No">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.loginID.required && $v.loginID.$error">Please enter login id.</div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <input :type="passwordTextboxType" 
              v-model.trim="password" 
              name="password" 
              id="password" 
              class="form-control"
              v-bind:class="{ 'is-invalid': $v.password.$error, 'is-valid': !$v.password.$invalid && !$v.password.$error  }"
              maxlength="20" 
              placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text pwd-hide-show" @click="pwdShowHide">
                <span class="fas navy" v-bind:class="{ 'fa-lock' : isPwdShowHide, 'fa-eye' : !isPwdShowHide }"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.password.required && $v.password.$error">Please enter password.</div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-navy">
              <input type="checkbox" v-model.trim="rememberMe" name="remember_me" id="remember_me" value="1">
              <label for="remember_me">
               Remember Me
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn bg-navy btn-block" :disabled="isDisabled">Login</button>
          </div>
        </div>

      </form>
      <!-- end form -->

      <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign in using Google+
        </a>
      </div>
      <div class="text-center">
        <a href="{{ route('client.auth.forgot.password') }}">Forgot password ?</a>
      </div>
      <div class="text-center">
        <a href="{{ route('client.auth.signup') }}">Create Account ?</a>
      </div>
    </div>
  </div><!-- /.card -->
</div>
@endsection

@push('page_js')
<script src="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
let pageVue = new Vue({
  el: '#clientSignIn',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      loginID: "{!! $loginId !!}",
      password: "{!! $loginPwd !!}",
      rememberMe: "{!! $remember !!}",
      isPwdShowHide: false,
      passwordTextboxType: 'password'
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.loginID == '' || this.password == '';
    }
  },
  validations: {
    loginID: {
        required
    },
    password: {
        required
    }
  },
  methods: {
    initData() {
        this.loginID = '';
        this.password = '';
    },
    pwdShowHide() {
      if (this.isPwdShowHide) {
        this.isPwdShowHide = false;
        this.passwordTextboxType = 'password';
      } else {
        this.isPwdShowHide = true;
        this.passwordTextboxType = 'text';
      }
    },
    signinSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.signupSubmitProcess();
      }
    },
    async signupSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('client.auth.signin.process') }}";
      const process = await axios({
          method: 'post',
          url: url,
          data: {
          loginid: _this.loginID,
          password: _this.password,
          remember_me: _this.rememberMe
          },
          headers: {'Content-Type': 'application/json'}
      })
      .then(function (response) {
          _this.isLoading = false;
          if (response.data.status === 200) {
              _this.initData();
              _this.$v.$reset();
              _this.$toastr.removeByType("error");
              _this.$toastr.s("Redirection to your account.", "Login Successfull!");
              Swal.fire({
                  icon: 'success',
                  title: 'Login Successfull!',
                  html: 'Login verified and system redirection to your account<br/>Please wait...',
                  showConfirmButton: false,
                  allowOutsideClick: false
              });
              setTimeout(function () { 
                  window.location.href = "{{ route('client.account.dashboard') }}";
              }, 3000);
          }
          if (response.data.status === 201) {
            if (response.data.body.content.nextAction != undefined) {
              if (response.data.body.content.nextAction.actionKey === 'askForResendVerificationMail') {
                _this.$toastr.e("Signup not completed. Please verify your email and complete the signup process, thankyou.", "Email Not Verified");
                Swal.fire({
                  title: 'Signup Not Completed',
                  html: `<p>Email verification is pending.</p> <p>We already sent verification mail at <strong>${_this.loginID}</strong>. If you have not got verification mail, Please click on the resend button.</p>`,
                  icon: 'info',
                  showCancelButton: false,
                  confirmButtonText: 'Resend Verification Mail',
                  allowOutsideClick: false
                }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Please Wait...',
                      html: 'We are sending the verification mail.',
                      willOpen () {
                        Swal.showLoading()
                      },
                      showConfirmButton: false,
                      allowOutsideClick: false
                    });
                    _this.resendVerificationMail(response.data.body.content.nextAction.actionLink);
                  }
                });
              } else {
                Swal.fire({
                  icon: 'info',
                  title: 'Your Signup Process Not Completed',
                  html: 'Your signup process in progress state. Please click <b>Next Button</b> to complete the signup process, Thankyou.',
                  confirmButtonText: 'Next',
                  allowOutsideClick: false
                }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Please Wait...',
                      willOpen () {
                        Swal.showLoading()
                      },
                      showConfirmButton: false,
                      allowOutsideClick: false
                    });
                    _this.nextStep(response.data.body.content.nextAction.actionLink);
                  }
                });
              }
            } else {
              _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
              setTimeout(function () { 
                  location.reload();
              }, 3000);
            }
          }
          if (response.data.status === 202) {
              _this.$toastr.e("We can not proceed with unverified mobile number. Please login with email or username.", "Unverified Mobile!");
              Swal.fire({
                  icon: 'info',
                  title: `Mobile Number Not Verified<br/>${_this.loginID}`,
                  html: 'We can not proceed with unverified mobile number. If you want to use your mobile number as a login id then please verifiy first.',
                  showConfirmButton: true,
                  allowOutsideClick: false
              })
          }
          if (response.data.status === 203) {
            _this.$toastr.e("Your account has been inactivated or blocked.", "Access Denied!");
            Swal.fire({
              icon: 'error',
              title: 'Access Denied!',
              html: 'Your account has been inactivated or blocked. Please contact to administrator, thankyou.',
              showConfirmButton: true,
              allowOutsideClick: false
            });
          }
      }).catch(function (error) {
          if (error.response.status == 422) {
            _this.isLoading = false;
            _this.validationErrors = error.response.data.body.content.validationErrors;
          } else if (error.response.status == 401) {
            _this.isLoading = false;
            _this.$toastr.e("Sorry! Login information is incorrect. Please proceed with valid credentials.", "Invalid Login!");
          } else {
            _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
            setTimeout(function () { 
                location.reload();
            }, 3000);
          }
      });
    },
    async resendVerificationMail(emailVerificationPostUrl) {
      var _this = this;
      const process = await axios({
        method: 'post',
        url: emailVerificationPostUrl,
        data: {
          email_id: _this.loginID
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Mail Sent! Please check your email',
          text: 'A verification email sent to your mail, please verify your email address, thankyou.',
          showConfirmButton: true,
          allowOutsideClick: false,
          confirmButtonText: 'Resend Verification Mail',
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Please Wait...',
              html: 'We are sending the verification mail.',
              willOpen () {
                Swal.showLoading()
              },
              showConfirmButton: false,
              allowOutsideClick: false
            });
            _this.resendVerificationMail(emailVerificationPostUrl);
          }
        });
      }).catch(function (error) {
        _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
        setTimeout(function () { 
          location.reload();
        }, 3000);
      });
    },
    async nextStep(redirectLink) {
      window.location.href = redirectLink;
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush