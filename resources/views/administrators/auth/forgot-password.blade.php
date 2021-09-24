@extends('administrators.auth.layout.layout')

@section('page_title', ' | Forgot Password')

@section('page_content')
<div class="card-body login-card-body" id="forgotPassword" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <p class="text-md font-weight-bold text-primary text-center">Forgot Password</p>
  <p class="text-center text-sm text-secondary">Please enter your registered email address</p>
  <hr class="m-2">
  <div class="px-4">
    @include('onex.validation_list')
    <form id="form" @submit.prevent="onSubmit" action="" method="POST">
      @csrf
      <label class='form-control-label'>Email Address</label>
      <div class="input-group">
        <input type="text" v-model.trim="emailId" name="email_id" maxlength="60" class="form-control" placeholder="Email Address">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-user"></span>
          </div>
        </div>
      </div>
      <div class="text-danger" v-if="!$v.emailId.required && $v.emailId.$error">Please enter email address</div>
      <div class="text-danger" v-if="!$v.emailId.email && $v.emailId.required && $v.emailId.$error">Please enter valid email address</div>
      <div class="row justify-content-center mt-3">
        <div class="col-12 text-center">
          <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Send Password Reset Link</button>
        </div>
      </div>
    </form>
  </div>
  <div class="row">
    <div class="col-12 text-center mt-4">
        <a href="{{ route('administrator.auth.signin') }}"><p class="text-primary mb-0">Sign In ?</p></a>
    </div>
  </div>
</div>
@endsection

@push('page_js')
<script type="text/javascript">
let app = new Vue({
  el: '#forgotPassword',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      emailId: ""
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
      email,
      maxLength: maxLength(60)
    }
  },
  methods: {
    onSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.onSubmitProcess();
      }
    },
    async onSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('administrator.auth.forgot.password.process') }}";
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
            _this.$toastr.removeByType("error");
            _this.$toastr.s("Reset password link has been sent to this email address.", "Mail Sent!");
            Swal.fire({
              icon: 'success',
              title: 'Reset Password Link Sent!',
              html: 'Reset password link has been sent to this email address. Please reset your new password.',
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
            _this.$toastr.e("Sorry! This email address not exist in our system. Please enter correct email and proceed.", "Invalid Email!");
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