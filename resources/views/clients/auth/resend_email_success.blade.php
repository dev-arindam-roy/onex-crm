@php
  $getUfname = $getUemail = '';
  if (isset($ufname)) {
    $getUfname = $ufname;
  }
  if (isset($uemail)) {
    $getUemail = $uemail;
  }
@endphp

@extends('clients.layout.auth')

@section('page_title', ' | Signup')

@push('page_css')
<link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('page_content')
<div class="register-box" id="resentMailSuccess" v-cloak>
  
</div>
<!-- /.register-box -->
@endsection

@push('page_js')
<script src="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
let pageVue = new Vue({
  el: '#resentMailSuccess',
  data() {
    return {
      userFname: '',
      userEmail: ''
    }
  },
  methods: {
    async resendVerificationMail() {
      var _this = this;
      const process = await axios({
        method: 'post',
        url: "{{ route('client.auth.signup.resendEmail') }}",
        data: {
          email_id: _this.userEmail
        },
        headers: {'Content-Type': 'application/json'}
      }).then(function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Mail Sent! Please check your email',
          text: 'A verification email sent to your mail, please verify your email address, thankyou.',
          showConfirmButton: true,
          allowOutsideClick: false,
          confirmButtonText: 'Resend Verification Mail'
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
            _this.resendVerificationMail();
          }
        });
      }).catch(function (error) {
        _this.$toastr.e("{{ config('onex.default_error_msg') }}", "Oops!");
        setTimeout(function () { 
          location.reload();
        }, 3000);
      });
    }
  },
  mounted() {
    _this = this;
    let uFname = "{!! $getUfname !!}";
    let uEmail = "{!! $getUemail !!}";
    _this.userFname = uFname;
    _this.userEmail = uEmail;
    _this.$toastr.s("Verification email has been sent again. Please check your email.", "Mail Sent");
    Swal.fire({
      icon: 'success',
      title: `<div align="center"><strong>${uFname}</strong><br/>Email verification mail sent</div>`,
      html: `Previous mail has been expired. <br/> We sent a new varification mail. Please check your email and verify your email address. <br/> <strong>${uEmail}</strong>`,
      showConfirmButton: true,
      allowOutsideClick: false,
      confirmButtonText: 'Resend Verification Mail'
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
        _this.resendVerificationMail();
      }
    });
  }
});
</script>
@endpush
