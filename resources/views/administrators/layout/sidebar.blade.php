<div class="sidebar">
  <nav>
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-header">Menu Navigation</li>
      <li class="nav-item">
        <a href="{{ route('administrator.account.dashboard') }}" class="nav-link @if(Route::currentRouteName() == 'administrator.account.dashboard') active @endif">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p class="px-2">
            Dashboard
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-users"></i>
          <p class="px-2">
            Client Management
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-user-tie"></i>
          <p class="px-2">
            Admin Management
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-boxes"></i>
          <p class="px-2">
            Master Boxes
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-file-export"></i>
          <p class="px-2">
            Reports
          </p>
        </a>
      </li>
    </ul>
  </nav>
</div>