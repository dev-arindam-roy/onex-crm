@include('clients.layout.includes.header')
<!-- Site wrapper -->
<div class="wrapper" id="ONEX_WRAPPER">
    @include('clients.layout.includes.header_nav')
    @include('clients.layout.includes.left_sidebar')
    @yield('page_content')
    @include('clients.layout.includes.footer_text')
</div>
<!-- ./wrapper -->
@include('clients.layout.includes.footer')