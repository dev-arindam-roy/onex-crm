@extends('clients.layout.auth')

@section('page_title', ' | Signup')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="clientSignUp" v-cloak>
  
  @include('onex.loading')
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Create Free Account</p>
      @include('onex.validation_list')

      <!-- form -->
      <form name="clientSignUpFrm" id="clientSignUpFrm" @submit.prevent="signupSubmit" method="POST">
      
        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="firstName" 
              name="first_name" 
              id="first_name" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.firstName.$error, 'is-valid': !$v.firstName.$invalid && !$v.firstName.$error  }" 
              maxlength="25"
              placeholder="First Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.firstName.required && $v.firstName.$error">Please enter first name.</div>
          <div class="text-danger ofs-14" v-if="!$v.firstName.regxAlfaWithSpace && $v.firstName.required && $v.firstName.$error">Please enter valid first name.</div>
          <div class="text-danger ofs-14" v-if="!$v.firstName.minLength && $v.firstName.regxAlfaWithSpace && $v.firstName.required && $v.firstName.$error">Minimum 3 characters required.</div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="lastName" 
              name="last_name" 
              id="last_name" 
              class="form-control"
              v-bind:class="{ 'is-invalid': $v.lastName.$error, 'is-valid': !$v.lastName.$invalid && !$v.lastName.$error  }"
              maxlength="16" 
              placeholder="Last Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.lastName.required && $v.lastName.$error">Please enter last name.</div>
          <div class="text-danger ofs-14" v-if="!$v.lastName.regxAlfaWithSpace && $v.lastName.required && $v.lastName.$error">Please enter valid last name.</div>
          <div class="text-danger ofs-14" v-if="!$v.lastName.minLength && $v.lastName.regxAlfaWithSpace && $v.lastName.required && $v.lastName.$error">Minimum 2 characters required.</div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <input type="email" 
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
          <div class="col-8">
            <div class="icheck-navy">
              <input type="checkbox" v-model.trim="agreeSignupTerms" name="agree_signup_terms" id="agree_signup_terms" value="1">
              <label for="agree_signup_terms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn bg-navy btn-block">Sign up</button>
          </div>
        </div>

      </form>
      <!-- end form -->

      <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>
      <div class="text-center">
        <a href="{{ route('client.auth.signin') }}">Already i have an account</a>
      </div>
    </div>
  </div><!-- /.card -->
</div>
@endsection

@push('page_js')
<script src="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
let pageVue = new Vue({
  el: '#clientSignUp',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      firstName: '',
      lastName: '',
      emailId: '',
      agreeSignupTerms: true,
    }
  },
  watch: {
        
  },
  computed: {
      
  },
  validations: {
    firstName: {
      required,
      regxAlfaWithSpace,
      minLength: minLength(3),
      maxLength: maxLength(25)
    },
    lastName: {
      required,
      regxAlfaWithSpace,
      minLength: minLength(3),
      maxLength: maxLength(25)
    },
    emailId: {
      required,
      regxEmailAddress,
      maxLength: maxLength(60)
    }
  },
  methods: {
    initData() {
      this.firstName = '';
      this.lastName = '';
      this.emailId = '';
      this.agreeSignupTerms = false;
    },
    signupSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.signupSubmitProcess();
      }
    },
    async signupSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('client.auth.signup.save') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          first_name: _this.firstName,
          last_name: _this.lastName,
          email_id: _this.emailId,
          agree_signup_terms: _this.agreeSignupTerms
        },
        headers: {'Content-Type': 'application/json'}
      })
      .then(function (response) {
        _this.isLoading = false;
        if (response.data.status === 200) {
          _this.initData();
          _this.$v.$reset();
          _this.$toastr.removeByType("error");
          _this.$toastr.s("Initial signup has been completed successfully.", "Thankyou!");
          Swal.fire({
            icon: 'success',
            title: 'Initial signup has been completed successfully',
            text: 'A verification email sent to your mail, please verify your email address, thankyou.',
            showConfirmButton: true,
            allowOutsideClick: false
          });
        }
        if (response.data.status === 201) {
          if (response.data.body.content.nextAction != undefined) {
            if (response.data.body.content.nextAction.actionKey === 'askForResendVerificationMail') {
              _this.$toastr.s("Initial signup has been completed, Please verify your email address and proceed to next step.", "Thankyou!");
              Swal.fire({
                title: 'Please Verify Your Email',
                html: `<p>Email verification is pending.</p> <p>We already sent verification mail at <strong>${_this.emailId}</strong>. If you have not got verification mail, Please click on the resend button.</p>`,
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
            } else if (response.data.body.content.nextAction.actionKey === 'goSignIn') {
              Swal.fire({
                icon: 'success',
                title: 'You Already Have Account',
                html: 'Please login with your credentials and access your onex account, thankyou.',
                confirmButtonText: 'Please Login',
                showCancelButton: true,
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
            } else {
              Swal.fire({
                icon: 'success',
                title: 'Initial signup has been completed successfully',
                html: 'Your email address verified has been verified successfully. Please click <b>Next Button</b> to complete the signup process.',
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
      })
      .catch(function (error) {
        if (error.response.status == 422) {
          _this.isLoading = false;
          _this.validationErrors = error.response.data.body.content.validationErrors;
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