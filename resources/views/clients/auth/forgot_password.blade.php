@extends('clients.layout.auth')

@section('page_title', ' | Forgot Password')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="clientForgotPassword" v-cloak>
  
  @include('onex.loading')
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Forgot Password</p>
      @include('onex.validation_list')
      
      <!-- form -->
      <form name="clientForgotPwdFrm" id="clientForgotPwdFrm" @submit.prevent="forgotPwdSubmit" method="POST">
      
        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="emailId" 
              name="email_id" 
              id="email_id" 
              class="form-control"
              v-bind:class="{ 'is-invalid': $v.emailId.$error, 'is-valid': !$v.emailId.$invalid && !$v.emailId.$error  }"
              maxlength="60" 
              placeholder="Email-Id">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.emailId.required && $v.emailId.$error">Please enter email-id.</div>
          <div class="text-danger ofs-14" v-if="!$v.emailId.regxEmailAddress && $v.emailId.required && $v.emailId.$error">Please enter valid email-id.</div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn bg-navy btn-block" :disabled="isDisabled">Send Password Reset Link</button>
          </div>
        </div>

      </form>
      <!-- end form -->
      <div class="text-center mt-3">
        <a href="{{ route('client.auth.signin') }}">Sign in ?</a>
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
  el: '#clientForgotPassword',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      emailId: ''
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.emailId == '';
    }
  },
  validations: {
    emailId: {
        required,
        regxEmailAddress
    }
  },
  methods: {
    forgotPwdSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.forgotPwdSubmitProcess();
      }
    },
    async forgotPwdSubmitProcess() {
        var _this = this;
        _this.isLoading = true;
        var url = "{{ route('client.auth.forgot.password.process') }}";
        const process = await axios({
            method: 'post',
            url: url,
            data: {
            email_id: _this.emailId
            },
            headers: {'Content-Type': 'application/json'}
        }).then(function (response) {
            if (response.data.status === 200) {
                _this.isLoading = false;
                _this.emailId = '';
                _this.$v.$reset();
                _this.$toastr.removeByType("error");
                _this.$toastr.s("Reset password link has been sent to this email address, thankyou.", "Mail Sent!");
                Swal.fire({
                    icon: 'success',
                    title: 'Reset Password Mail Sent',
                    html: 'We have sent the reset password link to this email address. Please reset your password<br/>Thankyou.',
                    showConfirmButton: true,
                    allowOutsideClick: false
                });
            }
            if (response.data.status === 201) {
                _this.isLoading = false;
                Swal.fire({
                    icon: 'info',
                    title: 'Signup Not Completed',
                    html: 'Your signup is in progress state, Please complete the signup process first, thankyou.',
                    confirmButtonText: 'Signup',
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
                        window.location.href = "{{ route('client.auth.signup') }}";
                    }
                });
            }
        }).catch(function (error) {
            if (error.response.status == 422) {
                _this.isLoading = false;
                _this.validationErrors = error.response.data.body.content.validationErrors;
            } else if (error.response.status == 401) {
                _this.isLoading = false;
                _this.$toastr.e("This email-id don't have any onex account.", "Invalid Email");
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