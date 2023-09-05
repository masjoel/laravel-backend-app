<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item ">
                <a href="{{ route('home') }}" class="nav-link "><i class="fas fa-dashboard"></i><span>Dashboard</span></a>
            </li>
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
            <li class="nav-item ">
                <a href="{{ route('user.index') }}" class="nav-link "><i class="fas fa-users"></i><span>User List</span></a>
            </li>
            @endif

    </aside>
</div>