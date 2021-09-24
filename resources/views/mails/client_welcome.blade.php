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
  <p style="text-align: center;">Your signup process has been completed successfully.</p>
  <p style="text-align: center;">We are able to create your onex account and your account information showing below.</p>
  <p style="text-align: center;">
    <span><strong>Account Name:</strong> {{ $user->first_name . ' ' . $user->last_name }}</span> <br/>
    <span><strong>Account Email:</strong> {{ $user->email_id }}</span> <br/>
    <span><strong>Account Number:</strong> {{ $organization->account_id }}</span> <br/>
    <span><strong>Organization:</strong> {{ $organization->organization_name }}</span> <br/>
    <span><strong>Hash ID:</strong> {{ $user->hash_id }}</span> <br/>
  </p>
  <p style="text-align: center; margin-top: 15px;">
    <a class="vbtn" href="{{ route('client.auth.signin') }}">Please Login To Your Account</a>
  </p>
</div>
<p>Thanks, <br/> Onex Team</p>
@endsection