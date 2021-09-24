<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="../../index3.html" class="nav-link">{{ __('label.menu.home') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @include('clients.layout.includes.header_nav_language')
    @include('clients.layout.includes.header_nav_notification')
    @include('clients.layout.includes.header_nav_user_profile')
  </ul>
</nav>
<!-- /.navbar -->