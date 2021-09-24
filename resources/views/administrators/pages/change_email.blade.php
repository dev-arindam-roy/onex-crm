@extends('administrators.layout.layout')

@section('page_title', ' | Change Email')

@section('page_content')
<div class="row mb-2" id="changeEmail" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <div class="row">
    <div class="col-sm-6">
      <h5 class="section-header">Change Email Address</h5>
    </div>
    <div class="col-sm-6"></div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          @include('onex.validation_list')
          <form id="emailFrm" @submit.prevent="onSubmit" method="post">
            <div class="form-group">
              <label class='form-control-label'>Current Email Address:</label>
              <input type="text" v-model.trim="currentEmail" class="form-control" placeholder="Current Email Address" readonly="readonly">
            </div>
            <div class="form-group">
              <label class='form-control-label'>New Email Address: <span class="text-danger"> * </span></label>
              <input type="text" v-model.trim="newEmail" class="form-control" placeholder="New Email Address" maxlength="60">
              <div class="text-danger" v-if="!$v.newEmail.required && $v.newEmail.$error">Please enter email-id.</div>
              <div class="text-danger" v-if="!$v.newEmail.email && $v.newEmail.required && $v.newEmail.$error">Please enter valid email-id.</div>
            </div>
            <div class="form-group text-right">
              <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Send Verification Mail</button>
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
  el: '#changeEmail',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      currentEmail: "{!! Auth::guard('admin')->user()->email_id !!}",
      newEmail: ''
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.currentEmail == '' || this.newEmail == '';
    }
  },
  validations: {
    newEmail: {
      required,
      email,
      maxLength: maxLength(60)
    }
  },
  methods: {
    onSubmit() {
      this.$v.$touch();
      if (!this.$v.$error) {
        if (this.currentEmail != this.newEmail) {
          this.areYouSureProcess();
        } else {
          this.newEmail = '';
          this.$v.$reset();
          this.$toastr.e("Current email and the new email are same. Please enter new email address.", "Sorry!");
        }
      }
    },
    areYouSureProcess() {
      _this = this;
      Swal.fire({
        icon: 'question',
        title: 'Are You Sure?',
        html: 'You want to change your current email address.',
        showConfirmButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        showCancelButton: true,
        allowOutsideClick: false
      }).then((result) => {
        if (result.isConfirmed) {
          _this.onSubmitProcess();
        }
        if (result.dismiss === Swal.DismissReason.cancel) {
          _this.newEmail = '';
          _this.$v.$reset();
        }
      });
    },
    async onSubmitProcess() {
      var _this = this;
      _this.isLoading = true;
      var url = "{{ route('administrator.account.changeemail.save') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          email_id: _this.newEmail
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        _this.validationErrors = [];
        if (response.data.status === 200) {
          _this.newEmail = '';
          _this.$v.$reset();
          _this.isLoading = false;
          _this.$toastr.s("Your account email has been updated successfully. Please verify this new email address.", "Done!");
          Swal.fire({
            icon: 'success',
            title: 'Verification Email Sent!',
            html: 'We sent verification mail to this email.Account going to logging off. <br/><strong>Please wait...</strong>',
            showConfirmButton: false,
            allowOutsideClick: false
          });
          setTimeout(function () { 
            window.location.href = "{{ route('administrator.auth.signin') }}";
          }, 3000);
        }
        if (response.data.status === 201) {
          _this.isLoading = false;
          _this.$toastr.e("This email associated with another account. Please try with another email address.", "Email Exist!");
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
    }
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush


