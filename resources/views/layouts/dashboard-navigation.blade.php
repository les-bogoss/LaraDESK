<div class="layout-dashboard-header">
    <ul>
        <li><a href="{{ route('dashboardData.index') }}" class="{{ request()->routeIs('dashboardData.index') ? 'active' : 'inactive' }}">DATA</a></li>
        <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users*') ? 'active' : 'inactive' }}">USERS</a></li>
        <li><a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles*') ? 'active' : 'inactive' }}">ROLES</a></li>
      </ul>
</div>  