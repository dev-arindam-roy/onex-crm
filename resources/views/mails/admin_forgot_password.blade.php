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
<p class="hi-text">Hi, {{ $admin->first_name }}</p>
<div style="text-align: center;">
  <h1 style="text-align: center;">Welcome to ONEX Master<br/></h1>
  <p style="text-align: center;">Please reset your password.</p>
  
  <p style="text-align: center;">
    <a class="vbtn" href="{{ route('administrator.auth.reset.password', array('token' => $reset_password->token)) }}">Click To Reset Your Password</a>
  </p>
</div>
<p>Thanks, <br/> Onex Team</p>
@endsection