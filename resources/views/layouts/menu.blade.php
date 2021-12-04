<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('dashboard.index') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>ড্যাশবোর্ড</p>
    </a>
</li>

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.users') }}" class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }} {{ Request::is('dashboard/users/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>ব্যবহারকারীগণ</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin' || Auth::user()->role == 'accountant')
<li class="nav-item">
    <a href="{{ route('dashboard.balance') }}" class="nav-link {{ Request::is('dashboard/balance') ? 'active' : '' }}">
        <i class="nav-icon fas fa-funnel-dollar"></i>
        <p>ব্যালেন্স</p>
    </a>
</li>
@endif

@if(Auth::user()->role != 'accountant')
<li class="nav-item">
    <a href="{{ route('dashboard.sites') }}" class="nav-link {{ Request::is('dashboard/sites') ? 'active' : '' }} {{ Request::is('dashboard/sites/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>সাইটসমূহ</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('dashboard.categories') }}" class="nav-link {{ Request::is('dashboard/categories') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tags"></i>
        <p>খাতসমূহ</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.monthly') }}" class="nav-link {{ Request::is('dashboard/monthly') ? 'active' : '' }} {{ Request::is('dashboard/monthly/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>মাসভিত্তিক জমা-খরচ</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.creditors') }}" class="nav-link {{ Request::is('dashboard/creditors') ? 'active' : '' }} {{ Request::is('dashboard/creditors/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-clock"></i>
        <p>পাওনাদারের হিসাব</p>
    </a>
</li>
@endif
{{-- <li class="nav-item">
    <a href="{{ route('dashboard.components') }}" class="nav-link {{ Request::is('dashboard/components') ? 'active' : '' }}">
        <i class="nav-icon fas fa-laptop-code"></i>
        <p>Components</p>
    </a>
</li> --}}
<li class="nav-item">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>লগআউট</p>
    </a>
</li>
