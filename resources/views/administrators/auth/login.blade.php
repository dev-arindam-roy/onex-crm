@php
  $loginId = '';
  $loginPwd = '';
  $remember = false;
  if (isset($_COOKIE) && !empty($_COOKIE['onexMasterLogID']) && !empty($_COOKIE['onexMasterLogPwd'])) {
    $loginId = $_COOKIE['onexMasterLogID'];
    $loginPwd = $_COOKIE['onexMasterLogPwd'];
    $remember = true;
  }
@endphp

@extends('administrators.auth.layout.layout')

@section('page_title', ' | Sign in')

@section('page_content')
<div class="card-body login-card-body" id="login-page" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <!-- <p class="login-box-msg"></p> -->
  <p class="text-md font-weight-bold text-primary text-center">Administrator Login</p>
  <hr class="m-2">
  <div class="px-4">
    @include('onex.validation_list')
    <form id="loginForm" @submit.prevent="onSubmit" action="" method="POST">
      @csrf
      
      <div class="form-group mb-2">
        <label class='form-control-label'>Email Address:</label>
        <div class="input-group">
          <input type="text" class="form-control" name="email_id" v-model="emailId" placeholder="Email Address" maxlength="60">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="text-danger" v-if="!$v.emailId.required && $v.emailId.$error">Please enter email address.</div>
        <div class="text-danger" v-if="!$v.emailId.email && $v.emailId.required && $v.emailId.$error">Please enter valid email address.</div>
      </div>

      <div class="form-group mb-2">
        <label class='form-control-label'>Password:</label>
        <div class="input-group">
          <input type="password" class="form-control" name="password" v-model="password" placeholder="Password" maxlength="20">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="text-danger" v-if="!$v.password.required && $v.password.$error">Please enter password.</div>
      </div>

      <div class="row mt-3">
        <!-- <div class="col-1"></div> -->
        <div class="col-6">
          <div style="margin-left: 21px;">
            <input type="checkbox" v-model.trim="isRememberMeChecked" class="form-check-input" name="remember_me" id="remember_me" value="1"> 
            <label class="form-control-label" for="remember_me">Remember Me</label>
          </div>
        </div>
        <div class="col-6 text-right">
          <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Login</button>
        </div>
      </div>
    </form> 
  </div>
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('administrator.auth.forgot.password') }}"><p class="text-primary mb-0 mt-2 mr-4">Forgot Password?</p></a>
    </div>
  </div>
</div>
@endsection

@push('page_js')
<script type="text/javascript">
let app = new Vue({
    el: '#login-page',
    data() {
      return {
        validationErrors: [],
        isLoading: true,
        emailId: "{!! $loginId !!}",
        password: "{!! $loginPwd !!}",
        isRememberMeChecked: "{!! $remember !!}"
      }
    },
    watch: {

    },
    computed: {
      isDisabled: function () {
        return this.emailId == '' || this.password == '';
      }
    },
    validations: {
      emailId: {
        required,
        email
      },
      password: {
        required
      }
    },
    methods: {
      initData() {
        this.emailId = '';
        this.password = '';
        this.isRememberMeChecked = false;
      },
      onSubmit() {
        this.$v.$touch();
        if (!this.$v.$error) {
          this.onSubmitProcess();
        }
      },
      async onSubmitProcess() {
        var _this = this;
        _this.isLoading = true;
        var url = "{{ route('administrator.auth.signin.process') }}";
        const process = await axios({
            method: 'post',
            url: url,
            data: {
              email_id: _this.emailId,
              password: _this.password,
              remember_me: _this.isRememberMeChecked
            },
            headers: {'Content-Type': 'application/json'}
        }).then(function (response) {
            if (response.data.status === 200) {
              _this.isLoading = false;
              _this.initData();
              _this.$v.$reset();
              _this.$toastr.removeByType("error");
              _this.$toastr.s("Redirecting to your account.", "Login Successfull!");
              Swal.fire({
                icon: 'success',
                title: 'Login Successfull!',
                html: 'Login verified and system redirection to your account<br/>Please wait...',
                showConfirmButton: false,
                allowOutsideClick: false
              });
              setTimeout(function () { 
                window.location.href = "{{ route('administrator.account.dashboard') }}";
              }, 3000);
            } 
            if (response.data.status === 201) {
              _this.isLoading = false;
              if (response.data.type == 'account_blocked') {
                _this.$toastr.e("Your account has been blocked or inactivated.", "Account Blocked!");
                Swal.fire({
                  icon: 'info',
                  title: 'Account Blocked!',
                  html: 'Your account has been blocked or inactivated by administrator.Please contact with onex administrator team, thanks.',
                  showConfirmButton: true,
                  allowOutsideClick: false
                });
              }
              if (response.data.type == 'email_not_verified') {
                _this.$toastr.e("Email verification pending. Please verify your email first.", "Unverified Email!");
                Swal.fire({
                  icon: 'info',
                  title: 'Email Not Verified',
                  html: 'We already sent verification mail to your email address. If you not got the mail, please click on Resend Button.',
                  showConfirmButton: true,
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
                    _this.resendVerificationMail();
                  }
                });
              }
            }
        }).catch(function (error) {
            if (error.response.status == 422) {
                _this.isLoading = false;
                _this.validationErrors = error.response.data.body.content.validationErrors;
            } else if (error.response.status == 401) {
              _this.isLoading = false;
              _this.$toastr.e("Sorry! email and password combination are incorrect. Please enter valid login details.", "Invalid Credential!");
            } else {
                _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
                setTimeout(function () { 
                    location.reload();
                }, 3000);
            }
        });
      },
      async resendVerificationMail() {
        var _this = this;
        const process = await axios({
          method: 'post',
          url: "{{ route('administrator.auth.verify.email.send') }}",
          data: {
            email_id: _this.emailId
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
              _this.resendVerificationMail();
            }
          });
        }).catch(function (error) {
          _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
          setTimeout(function () { 
            location.reload();
          }, 3000);
        });
      }
    },
    mounted() {
      this.isLoading = false;
    }
});
</script>
@endpush