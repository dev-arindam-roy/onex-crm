@extends('clients.layout.auth')

@section('page_title', ' | Signup')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="signUpStep4" v-cloak>

  @include('onex.loading')  
  @include('onex.validation_toastr')

  <div class="card card-outline card-navy">
    <div class="card-header text-center">
      <a href="{{ route('client.auth.signup') }}" class="h1"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><b>ONEX-CRM</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Final Step</p>
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
                <li class="active">
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
        
        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="organizationName" 
              name="organization_name" 
              id="organization_name" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.organizationName.$error, 'is-valid': !$v.organizationName.$invalid && !$v.organizationName.$error  }" 
              maxlength="60"
              placeholder="Organization Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-sitemap navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.organizationName.required && $v.organizationName.$error">Please enter your organization name.</div>
          <div class="text-danger ofs-14" v-if="!$v.organizationName.regxOrganizationName && $v.organizationName.required && $v.organizationName.$error">Please enter valid organization name.</div>
          <div class="text-danger ofs-14" v-if="!$v.organizationName.minLength && $v.organizationName.regxOrganizationName && $v.organizationName.required && $v.organizationName.$error">Minimum 3 characters required.</div>
        </div>

        <div class="form-group">
          <div class="input-group">
            <input type="text" 
              v-model.trim="brandName" 
              name="business_name" 
              id="business_name" 
              class="form-control" 
              v-bind:class="{ 'is-invalid': $v.brandName.$error, 'is-valid': $v.brandName.$model != '' && !$v.brandName.$invalid && !$v.brandName.$error  }" 
              maxlength="30"
              placeholder="Brand Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="far fa-registered navy"></span>
              </div>
            </div>
          </div>
          <div class="text-danger ofs-14" v-if="!$v.brandName.regxBrandName && $v.brandName.$error">Please enter valid business name.</div>
          <div class="text-danger ofs-14" v-if="!$v.brandName.minLength && $v.brandName.regxBrandName && $v.brandName.$error">Minimum 3 characters required.</div>
        </div>
        
        <div class="row">
          <!-- /.col -->
          <div class="col-6">
            <button type="button" class="btn bg-navy btn-block" @click="backStep">Back</button>
          </div>
          <div class="col-6">
            <button type="submit" class="btn bg-navy btn-block" :disabled="!isDisabled">Finish</button>
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
  el: '#signUpStep4',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      organizationName: '',
      brandName: ''
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.organizationName != '' && this.organizationName != '';
    }
  },
  validations: {
    organizationName: {
      required,
      regxOrganizationName,
      minLength: minLength(3),
      maxLength: maxLength(60)
    },
    brandName: {
      regxBrandName,
      minLength: minLength(3),
      maxLength: maxLength(30)
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
      var url = "{{ route('client.auth.signup.stepFour.save') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          organization_name: _this.organizationName,
          business_name: _this.brandName
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        if (response.data.status === 200) {
          if (response.data.body.content.user != undefined && response.data.body.content.orgInfo != undefined && response.data.body.content.orgInfo != null) {
            _this.isLoading = false;
            let userInfo = response.data.body.content.user;
            let orgInfo = response.data.body.content.orgInfo;
            _this.$toastr.s("You have completed all steps successfully, thankyou.", "Account Created Successfully!");
            Swal.fire({
              title: 'Signup Completed!',
              html: `<p><u>Account Information</u></p> <p><strong>Name:</strong> ${userInfo.first_name} ${userInfo.last_name}<br/><strong>Email:</strong> ${userInfo.email_id}<br/><strong>Account No:</strong> ${orgInfo.account_id}</p>`,
              icon: 'success',
              showCancelButton: false,
              confirmButtonText: 'Please Login',
              allowOutsideClick: false
            }).then((result) => {
              if (result.isConfirmed) {
                Swal.fire({
                  title: 'Please Wait...',
                  html: 'We are redirecting to login.',
                  willOpen () {
                    Swal.showLoading()
                  },
                  showConfirmButton: false,
                  allowOutsideClick: false
                });
                window.location.href = "{{ route('client.auth.signin') }}";
              }
            });
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
      window.location.href = "{{ route('client.auth.signup.stepThree') }}";
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush
