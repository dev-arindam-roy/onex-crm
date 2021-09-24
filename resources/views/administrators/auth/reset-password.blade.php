@extends('administrators.auth.layout.layout')

@section('page_title', ' | Reset Password')

@section('page_content')
<div class="card-body login-card-body" id="resetPassword" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <p class="text-md font-weight-bold text-primary text-center">Reset Password</p>
  <hr class="m-2">
  <div class="px-4">
    @include('onex.validation_list')
    <form id="form" @submit.prevent="onSubmit" action="" method="POST">
      @csrf
      <div class="form-group">
        <label class='form-control-label'>Email Address</label>
        <input type="text" v-model.trim="emailId" name="email_id" class="form-control" placeholder="Email Address" readonly="readonly" style="pointer-events: none;">
        <div class="text-danger" v-if="!$v.emailId.required && $v.emailId.$error">Please enter email address</div>
        <div class="text-danger" v-if="!$v.emailId.email && $v.emailId.required && $v.emailId.$error">Please enter valid email address</div>
      </div>

      <div class="form-group">
        <label class='form-control-label'>Password</label>
        <input type="password" v-model.trim="password" name="password" class="form-control" placeholder="New Password">
        <div class="text-danger" v-if="!$v.password.required && $v.password.$error">Please enter password.</div>
        <div class="text-danger" v-if="!$v.password.regxPassword && $v.password.required && $v.password.$error">Please enter a strong password.</div>
        <div class="text-danger" v-if="!$v.password.minLength && $v.password.regxPassword && $v.password.required && $v.password.$error">Minimum 8 characters required.</div>
      </div>

      <div class="form-group">
        <label class='form-control-label'>Confirm Password</label>
        <input type="password" v-model.trim="confirmPassword" name="confirm_password" class="form-control" placeholder="Re-enter Password">
        <div class="text-danger ofs-14" v-if="!$v.confirmPassword.required && $v.confirmPassword.$error">Please enter password.</div>
        <div class="text-danger ofs-14" v-if="!$v.confirmPassword.sameAs && $v.confirmPassword.required && $v.confirmPassword.$error">Confirm password not match.</div>
      </div>

      <div class="row justify-content-center">
        <div class="col-6 text-center">
          <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Reset Password</button>
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
  el: '#resetPassword',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      emailId: "{!! $reset_password->email_id !!}",
      password: "",
      confirmPassword: ""
    }
  },
  watch: {

  },
  computed: {
    isDisabled: function () {
      return this.emailId == '' || this.password == '' || this.confirmPassword == '';
    }
  },
  validations: {
    emailId: {
      required,
      email,
      maxLength: maxLength(60)
    },
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
    onSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        this.onSubmitProcess();
      }
    },
    async onSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('administrator.auth.reset.password.save', array('token' => $reset_password->token)) }}";
      const process = await axios({
          method: 'post',
          url: url,
          data: {
            email_id: _this.emailId,
            password: _this.password,
            confirm_password: _this.confirmPassword
          },
          headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
          if (response.data.status === 200) {
            _this.isLoading = false;
            _this.$toastr.removeByType("error");
            _this.$toastr.s("Your password has been reset successfully.", "Password Reset!");
            Swal.fire({
              icon: 'success',
              title: 'Password Reset Successfull!',
              html: 'Your password has been reset successfully.<br/>Please wait...',
              showConfirmButton: false,
              allowOutsideClick: false
            });
            setTimeout(function () { 
              window.location.href = "{{ route('administrator.auth.signin') }}";
            }, 3000);
          } 
      }).catch(function (error) {
          if (error.response.status == 422) {
              _this.isLoading = false;
              _this.validationErrors = error.response.data.body.content.validationErrors;
          } else if (error.response.status == 404) {
            _this.$toastr.e("Sorry! Something went wrong. Please try again.", "Oops!");
            setTimeout(function () { 
              window.location.href = "{{ route('administrator.auth.signin') }}";
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
