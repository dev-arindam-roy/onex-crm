@php
  $isPasswordAlreadySet = false;
  if (isset($user) && !empty($user)) {
    if ($user->password != '' && $user->password != md5($user->hash_id)) {
      $isPasswordAlreadySet = true;
    }
  }
@endphp

@extends('clients.layout.auth')

@section('page_title', ' | Signup')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="signUpStep3" v-cloak>

  @include('onex.loading')
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign up In-progress</p>
      @include('onex.validation_list')
      <div class="wizard">
        <div class="wizard-inner">
          <div class="connecting-line"></div>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#"><span class="round-tab">1 </span></a>
                </li>
                <li class="active">
                    <a href="#"><span class="round-tab">2 </span></a>
                </li>
                <li class="disabled">
                    <a href="#"><span class="round-tab">3 </span></a>
                </li>
                <li class="disabled">
                    <a href="#"><span class="round-tab">4 </span></a>
                </li>
            </ul>
          </div>
        </div>

      <!-- form -->
      <form name="clientSignUpFrm" id="clientSignUpFrm" @submit.prevent="signupSubmit" method="POST">
        
        <div v-if="!isPasswordAlreadySet">
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
            <div class="text-danger ofs-14" v-if="!$v.confirmPassword.required && $v.confirmPassword.$error">Please enter confirm password.</div>
            <div class="text-danger ofs-14" v-if="!$v.confirmPassword.sameAs && $v.confirmPassword.required && $v.confirmPassword.$error">Confirm password not match.</div>
          </div>

          <div class="row">
            <div class="col-12 mb-3">
              <button type="button" class="btn btn-block btn-outline-navy btn-xs" @click="showHidePassword">@{{showHideText}}</button>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- /.col -->
          <div class="col-12 mb-2" v-if="isPasswordAlreadySet">
            <button type="button" class="btn bg-navy btn-block" @click="resetPassword"> <i class="fas fa-key"></i> Reset Password</button>
          </div>
          <div class="col-6">
            <button type="button" class="btn bg-navy btn-block" @click="backStep">Back</button>
          </div>
          <div class="col-6">
            <button type="submit" class="btn bg-navy btn-block" :disabled="!isDisabled" v-if="!isPasswordAlreadySet">Save & Next</button>
            <button type="button" class="btn bg-navy btn-block" @click="nextStep" v-if="isPasswordAlreadySet">Next</button>
          </div>
          <div class="col-12 mt-2" v-if="!isPasswordAlreadySet && isResetButtonClicked">
            <button type="button" class="btn bg-danger btn-block" @click="resetCancel">Cancel</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    
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
  el: '#signUpStep3',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      password: "",
      confirmPassword: "",
      isPasswordAlreadySet: "{!! $isPasswordAlreadySet !!}",
      isResetButtonClicked: false,
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
    signupSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.signupSubmitProcess();
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
    async signupSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('client.auth.signup.stepThree.save') }}";
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
            _this.$toastr.s("Your account password has been set successfully, thankyou.", "Done!");
            setTimeout(function () { 
              window.location.href = "{{ route('client.auth.signup.stepFour') }}";
            }, 3000);
          }
        }
      }).catch(function (error) {
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
    backStep() {
      this.isLoading = true;
      window.location.href = "{{ route('client.auth.signup.stepTwo') }}";
    },
    nextStep() {
      this.isLoading = true;
      window.location.href = "{{ route('client.auth.signup.stepFour') }}";
    },
    resetPassword() {
      this.isPasswordAlreadySet = false;
      this.isResetButtonClicked = true;
    },
    resetCancel() {
      this.isPasswordAlreadySet = true;
      this.isResetButtonClicked = false;
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush
