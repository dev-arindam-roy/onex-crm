@extends('mails.layout')
@push('mail_css')
<style>
.hi-text {
  font-size: 16px;
}
h1 {
  text-align:center;
  color: #ddd;
}
.vbtn {
  width: 200px;
  text-align: center;
  background-color: #001f3f;
  color: #fff;
  font-weight: 700;
  border:2px solid #001f3f;
  border-radius: 4px;
  padding: 10px;
  letter-spacing: 1px;
  word-spacing: 5px;
}
a.vbtn {
  text-decoration: none;
  color: #fff;
}
.resend-txt {
  font-size: 18px;
  font-weight: bold;
  text-align:center;
}
</style>
@endpush
@section('mail_content')
<p class="hi-text">Hi, {{ $user->first_name }}</p>
<div style="text-align: center;">
  <h1 style="text-align: center;">Welcome to ONEX-CRM<br/><small>We provides smart solutions for your business</small></h1>
  <p style="text-align: center;">Initial signup has been done. Please verify your email address.</p>
  @if($mailType == 'expired')
    <p class="resend-txt">Previous verification mail has been expired. We sent this new email for the verification process.</p>
  @endif
  @if($mailType == 'resend')
    <p class="resend-txt">Resent again! We sent this new email for the verification process.</p>
  @endif
  <p style="text-align: center;">
    <a class="vbtn" href="{{ route('client.auth.signup.emailverification', array('token' => $user->email_verify_token)) }}">Click To Verify Your Email</a>
  </p>
</div>
<p>Thanks, <br/> Onex Team</p>
@endsection