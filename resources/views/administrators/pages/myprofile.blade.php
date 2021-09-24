@extends('administrators.layout.layout')

@section('page_title', ' | My Profile')

@section('page_content')
<div class="row mb-2" id="myProfile" v-cloak>
  @include('onex.loading')
  @include('onex.validation_toastr')
  <div class="row">
    <div class="col-sm-6">
      <h5 class="section-header">My Profile</h5>
    </div>
    <div class="col-sm-6"></div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          @include('onex.validation_list')
          <form id="profileFrm" @submit.prevent="onSubmit" method="post">
            <div class="form-group">
              <label class='form-control-label'>Email Address:</label>
              <input type="text" v-model.trim="emailId" class="form-control" placeholder="Email Address" readonly="readonly">
              <div class="text-right mt-1"><a href="{{ route('administrator.account.changeemail') }}"><span class="ofs-12">Change Email ?</span></a></div>
            </div>
            <div class="form-group">
              <label class='form-control-label'>First Name:<span class="text-danger"> * </span></label>
              <input type="text" v-model.trim="firstName" maxlength="30" class="form-control" placeholder="First Name">
              <div class="text-danger" v-if="!$v.firstName.required && $v.firstName.$error">Please enter first name.</div>
              <div class="text-danger" v-if="!$v.firstName.regxAlfaWithSpace && $v.firstName.required && $v.firstName.$error">Please enter valid first name.</div>
              <div class="text-danger" v-if="!$v.firstName.minLength && $v.firstName.regxAlfaWithSpace && $v.firstName.required && $v.firstName.$error">Minimum 3 characters required.</div>
            </div>
            <div class="form-group">
              <label class='form-control-label'>Last Name:<span class="text-danger"> * </span></label>
              <input type="text" v-model.trim="lastName" maxlength="30" class="form-control" placeholder="Last Name">
              <div class="text-danger" v-if="!$v.lastName.required && $v.lastName.$error">Please enter last name.</div>
              <div class="text-danger" v-if="!$v.lastName.regxAlfaWithSpace && $v.lastName.required && $v.lastName.$error">Please enter valid last name.</div>
              <div class="text-danger" v-if="!$v.lastName.minLength && $v.lastName.regxAlfaWithSpace && $v.lastName.required && $v.lastName.$error">Minimum 2 characters required.</div>
            </div>
            <div class="form-group">
              <label class='form-control-label'>Mobile Number:</label>
              <input type="text" v-model.trim="mobileNumber" maxlength="12" class="form-control" placeholder="Mobile Number">
              <div class="text-danger ofs-14" v-if="!$v.mobileNumber.regxMobileNumber && $v.mobileNumber.$error">Please enter 10 digits mobile number.</div>
            </div>
            <div class="form-group text-right">
              <button type="submit" class="btn btn-outline-primary" :disabled="isDisabled">Save Changes</button>
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
  el: '#myProfile',
  data() {
    return {
      validationErrors: [],
      isLoading: true,
      emailId: "{!! Auth::guard('admin')->user()->email_id !!}",
      firstName: "{!! Auth::guard('admin')->user()->first_name !!}",
      lastName: "{!! Auth::guard('admin')->user()->last_name !!}",
      mobileNumber: "{!! Auth::guard('admin')->user()->mobile_number !!}"
    }
  },
  watch: {
        
  },
  computed: {
    isDisabled: function () {
      return this.firstName == '' || this.lastName == '';
    }
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
    mobileNumber: {
      regxMobileNumber,
      minLength: minLength(10),
      maxLength: maxLength(12)
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
      var url = "{{ route('administrator.account.myprofile.update') }}";
      const process = await axios({
        method: 'post',
        url: url,
        data: {
          first_name: _this.firstName,
          last_name: _this.lastName,
          mobile_number: _this.mobileNumber
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        _this.validationErrors = [];
        if (response.data.status === 200) {
          _this.isLoading = false;
          _this.$toastr.s("Your profile has been updated successfully.", "Done!");
          Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            html: 'Your profile has been updated successfully.<br/><strong>Please wait...</strong>',
            showConfirmButton: false,
            allowOutsideClick: false
          });
          setTimeout(function () { 
            location.reload();
          }, 3000);
        }
        if (response.data.status === 201) {
          _this.isLoading = false;
          _this.$toastr.e("This mobile number already associated with another account. Please try with another number.", "Mobile Exist!");
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


