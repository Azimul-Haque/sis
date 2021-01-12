<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('dashboard.index') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Dashboard</p>
    </a>
</li>
@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.users') }}" class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
    </a>
</li>
@endif
<li class="nav-item">
    <a href="{{ route('dashboard.balance') }}" class="nav-link {{ Request::is('dashboard/balance') ? 'active' : '' }}">
        <i class="nav-icon fas fa-hand-holding-usd"></i>
        <p>Balance</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard.sites') }}" class="nav-link {{ Request::is('dashboard/sites') ? 'active' : '' }} {{ Request::is('dashboard/sites/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>Sites</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard.categories') }}" class="nav-link {{ Request::is('dashboard/categories') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tags"></i>
        <p>Categories</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard.components') }}" class="nav-link {{ Request::is('dashboard/components') ? 'active' : '' }}">
        <i class="nav-icon fas fa-laptop-code"></i>
        <p>Components</p>
    </a>
</li>
<li class="nav-item">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
