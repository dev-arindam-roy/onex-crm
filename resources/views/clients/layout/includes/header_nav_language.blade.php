<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-language fa-2x"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right p-0">
        <a href="{{ route('lang', array('lang' => 'en')) }}" class="dropdown-item @if(Session::has('locale')) @if(Session::get('locale') == 'en') active @endif @else active @endif">
        <i class="@if(Session::has('locale')) @if(Session::get('locale') == 'en') fas fa-check mr-2 @else mr-4 @endif @else fas fa-check mr-2 @endif"></i> English
        </a>
        <a href="{{ route('lang', array('lang' => 'hi')) }}" class="dropdown-item @if(Session::has('locale') && Session::get('locale') == 'hi') active @endif">
        <i class="@if(Session::has('locale') && Session::get('locale') == 'hi') fas fa-check mr-2 @else mr-4 @endif"></i> हिंदी
        </a>
        <a href="{{ route('lang', array('lang' => 'bn')) }}" class="dropdown-item @if(Session::has('locale') && Session::get('locale') == 'bn') active @endif">
        <i class="@if(Session::has('locale') && Session::get('locale') == 'bn') fas fa-check mr-2 @else mr-4 @endif"></i> বাংলা
        </a>
    </div>
</li>