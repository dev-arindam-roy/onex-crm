<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style>
    .container {
      width: 600px;
      padding: 15px;
      margin: 15px auto;
      border-top: 5px solid #001f3f;
      border-left: 1px solid #ddd;
      border-right: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }
    .header-box {
      text-align: center;
    }
    .body-box {
      min-width:400px;
    }
    .footer-box {
      background-color: #ddd;
      text-align:center;
      padding-top:10px;
      padding-bottom: 10px;
    }
    hr {
      border: 0.2px solid #001f3f;
      background-color: #001f3f;
    }
    .onex {
      font-size: 24px;
      color: #001f3f;
      font-weight: bold;
    }
    a {
      text-decoration: none;
    }
    .foot-text {
      font-size: 14px;
    }
  </style>
  @stack('mail_css')
</head>
<body>
    <div class="container">
        <div class="header-box">
          <a href="{{ route('client.auth.signup') }}"><img src="{{ asset(config('onex.assets_path') . '/images/logo.svg') }}" style="width: 50px;"><br/><span class="onex">ONEX-CRM</span></a>
        </div>
        <hr/>
        <div class="body-box">
            @yield('mail_content')
        </div>
        <hr/>
        <div class="footer-box">
          <p class="foot-text">
            <a href="{{ route('client.auth.signup') }}">
              ONEX-CRM <br/>
              A Smart Business Solution

            </a>
          </p>
        </div>
    </div>
</body>
</html>
