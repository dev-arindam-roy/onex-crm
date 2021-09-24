@extends('administrators.layout.layout')

@section('page_title', ' | Change Password')

@section('page_content')
<div class="row mb-2" id="changePassword" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <div class="row">
    <div class="col-sm-6">
      <h5 class="section-header">Change Password</h5>
    </div>
    <div class="col-sm-6"></div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          @include('onex.validation_list')
          <form id="passwordFrm" @submit.prevent="onSubmit" method="post">
            <div class="form-group">
              <label class='form-control-label'>Current Password:<span class="text-danger"> * </span></label>
              <input :type="passwordOrText" v-model.trim="currentPassword" maxlength="20" class="form-control" placeholder="Current Password">
              <div class="text-danger" v-if="!$v.currentPassword.required && $v.currentPassword.$error">Please enter current password.</div>
            </div>
            <div class="form-group">
              <label class='form-control-label'>New Password:<span class="text-danger"> * </span></label>
              <input :type="passwordOrText" v-model.trim="newPassword" maxlength="20" class="form-control" placeholder="New Password" oncopy="return false">
              <div class="text-danger" v-if="!$v.newPassword.required && $v.newPassword.$error">Please enter new password.</div>
              <div class="text-danger" v-if="!$v.newPassword.regxPassword && $v.newPassword.required && $v.newPassword.$error">Please enter a strong password.</div>
              <div class="text-danger" v-if="!$v.newPassword.minLength && $v.newPassword.regxPassword && $v.newPassword.required && $v.newPassword.$error">Minimum 8 characters required.</div>
            </div>
            <div class="form-group">
              <label class='form-control-label'>Confirm Password:<span class="text-danger"> * </span></label>
              <input :type="passwordOrText" v-model.trim="confirmPassword" maxlength="20" class="form-control" placeholder="Confirm Password" onpaste="return false">
              <div class="text-danger ofs-14" v-if="!$v.confirmPassword.required && $v.confirmPassword.$error">Please enter confirm password.</div>
              <div class="text-danger ofs-14" v-if="!$v.confirmPassword.sameAs && $v.confirmPassword.required && $v.confirmPassword.$error">Confirm password not match.</div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <a href="javascript:void(0);" class="btn btn-outline-secondary" @click="showHidePassword">
                  <i class="far fa-eye" v-bind:class="{ 'fa-eye' : passwordOrText == 'password', 'fa-eye-slash' : passwordOrText == 'text' }"></i>
                </a>
              </div>
              <div class="col-sm-8 text-right">
                <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Change Password</button>
              </div>
            </div>
          </form>
        <div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page_js')
<script>
let appVue = new Vue({
  el: '#changePassword',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      currentPassword: '',
      newPassword: '',
      confirmPassword: '',
      passwordOrText: 'password'
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.currentPassword == '' || this.newPassword == '' || this.confirmPassword == '';
    }
  },
  validations: {
    currentPassword: {
      required
    },
    newPassword: {
      required,
      regxPassword,
      minLength: minLength(8),
      maxLength: maxLength(20)
    },
    confirmPassword: {
      required,
      sameAs: sameAs('newPassword'),
      maxLength: maxLength(20)
    }
  },
  methods: {
    initData() {
      this.currentPassword = '';
      this.newPassword = '';
      this.confirmPassword = '';
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
      var url = "{{ route('administrator.account.changepassword.save') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          current_password: _this.currentPassword,
          new_password: _this.newPassword,
          confirm_password: _this.confirmPassword
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        _this.validationErrors = [];
        if (response.data.status === 200) {
          _this.initData();
          _this.$v.$reset();
          _this.isLoading = false;
          _this.$toastr.s("Your password has been updated successfully.", "Done!");
          Swal.fire({
            icon: 'success',
            title: 'Password Changed',
            html: 'Your password has been changed successfully.<br/>Please login with new password.<br/><strong>Please wait...</strong>',
            showConfirmButton: false,
            allowOutsideClick: false
          });
          setTimeout(function () { 
            window.location.href = "{{ route('administrator.auth.signin') }}";
          }, 3000);
        }
        if (response.data.status === 201) {
          _this.isLoading = false;
          _this.$toastr.e("Current password is incorrect. Please try again.", "Sorry!");
        }
      }).catch(function (error) {
        _this.validationErrors = [];
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
    showHidePassword() {
      if (this.passwordOrText == 'password') {
        this.passwordOrText = 'text';
      } else {
        this.passwordOrText = 'password';
      }
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush


