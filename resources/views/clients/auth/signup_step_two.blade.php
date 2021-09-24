@php
  $usrnm = $mobno = '';
  if (isset($user) && !empty($user)) {
    if ($user->username != '' && $user->username != md5($user->hash_id)) {
      $usrnm = $user->username;
    }
    if ($user->mobile_number != '') {
      $mobno = $user->mobile_number;
    }
  }
@endphp

@extends('clients.layout.auth')

@section('page_title', ' | Signup')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="signUpStep2" v-cloak>

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
                  <a href="javascript:void(0);"><span class="round-tab">1 </span></a>
                </li>
                <li class="disabled">
                  <a href="javascript:void(0);"><span class="round-tab">2 </span></a>
                </li>
                <li class="disabled">
                  <a href="javascript:void(0);"><span class="round-tab">3 </span></a>
                </li>
                <li class="disabled">
                  <a href="javascript:void(0);"><span class="round-tab">4 </span></a>
                </li>
            </ul>
          </div>
        </div>
      <!-- form -->
      <form name="clientSignUpFrm" id="clientSignUpFrm" @submit.prevent="signupSubmit" method="POST">
        
        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="userName" 
              name="username" 
              id="username" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.userName.$error, 'is-valid': !$v.userName.$invalid && !$v.userName.$error  }" 
              maxlength="25"
              placeholder="Username"
              :readonly="isInputReadonly">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-at navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.userName.required && $v.userName.$error">Please enter an username.</div>
          <div class="text-danger ofs-14" v-if="!$v.userName.regxAlfaWithOutSpace && $v.userName.required && $v.userName.$error">Only accept alpha-numeric characters.</div>
          <div class="text-danger ofs-14" v-if="!$v.userName.minLength && $v.userName.regxAlfaWithOutSpace && $v.userName.required && $v.userName.$error">Minimum 6 characters required.</div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="mobileNumber" 
              name="mobile_number" 
              id="mobile_number" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.mobileNumber.$error, 'is-valid': !$v.mobileNumber.$invalid && !$v.mobileNumber.$error  }" 
              maxlength="12"
              placeholder="Mobile Number"
              :readonly="isInputReadonly">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-mobile-alt navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.mobileNumber.required && $v.mobileNumber.$error">Please enter mobile number.</div>
          <div class="text-danger ofs-14" v-if="!$v.mobileNumber.regxMobileNumber && $v.mobileNumber.required && $v.mobileNumber.$error">Please enter 10 digits mobile number.</div>
        </div>

        <div class="row" v-if="isInputReadonly">
          <!-- /.col -->
          <div class="col-6">
            <button type="button" class="btn bg-navy btn-block" @click="resetUserName">Reset</button>
          </div>
          <div class="col-6">
            <button type="button" class="btn bg-navy btn-block" @click="nextStep">Next</button>
          </div>
          <!-- /.col -->
        </div>

        <div class="row" v-if="!isInputReadonly">
          <!-- /.col -->
          <div class="col-6" v-if="isResetButtonClicked">
            <button type="button" class="btn bg-danger btn-block" @click="resetCancel">Cancel</button>
          </div>
          <div v-bind:class="{ 'col-6': isResetButtonClicked, 'col-12': !isResetButtonClicked }">
            <button type="submit" class="btn bg-navy btn-block" :disabled="!isDisabled">@{{submitButtonText}}</button>
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
  el: '#signUpStep2',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      isInputReadonly: false,
      userName: "{!! $usrnm !!}",
      mobileNumber: "{!! $mobno !!}",
      submitButtonText: 'Save & Next',
      isResetButtonClicked: false
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.userName != '' && this.mobileNumber != '';
    }
  },
  validations: {
    userName: {
      required,
      regxAlfaWithOutSpace,
      minLength: minLength(6),
      maxLength: maxLength(25)
    },
    mobileNumber: {
      required,
      regxMobileNumber,
      minLength: minLength(10),
      maxLength: maxLength(12)
    }
  },
  methods: {
    signupSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.signupSubmitProcess();
      }
    },
    async signupSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('client.auth.signup.stepTwo.save') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          username: _this.userName,
          mobile_number: _this.mobileNumber
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        if (response.data.status === 200) {
          if (response.data.body.content.user != undefined) {
            _this.$toastr.removeByType("error");
            _this.$toastr.s("Your username has been set successfully, thankyou.", "Done!");
            setTimeout(function () { 
              window.location.href = "{{ route('client.auth.signup.stepThree') }}";
            }, 3000);
          }
        }
        if (response.data.status === 201) {
          if (response.data.body.content.isExistUserName) {
            _this.isLoading = false;
            _this.$toastr.e("Please try with new username. This username already used by another user.", "Username Exist");
            Swal.fire({
              icon: 'error',
              title: 'Username Already Exist',
              text: 'Please try with new username. This username already used by another user.',
              showConfirmButton: true,
              allowOutsideClick: false
            });
          } else if (response.data.body.content.isExistMobile) {
            _this.isLoading = false;
            _this.$toastr.e("Please proceed with another mobile number.", "Mobile Number Exist");
            Swal.fire({
              icon: 'error',
              title: 'Mobile Number Already Exist',
              text: 'Please try with another mobile number. This mobile number already used by another user.',
              showConfirmButton: true,
              allowOutsideClick: false
            });
          } else {
            _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
            setTimeout(function () { 
              location.reload();
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
    nextStep() {
      this.isLoading = true;
      window.location.href = "{{ route('client.auth.signup.stepThree') }}";
    },
    resetUserName() {
      this.isInputReadonly = false;
      this.submitButtonText = 'Save';
      this.isResetButtonClicked = true;
    },
    resetCancel() {
      this.isInputReadonly = true;
      this.isResetButtonClicked = false;
    }
  },
  mounted() {
    this.isLoading = false;
    if (this.userName != '' && this.mobileNumber != '') {
      this.isInputReadonly = true;
    }
  }
});
</script>
@endpush
