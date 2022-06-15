<div class="layout-dashboard-header">
    <ul>
        <li><a href="{{ route('dashboardData.index') }}" class="{{ request()->routeIs('dashboardData.index') ? 'active' : 'inactive' }}">DATA</a></li>
        <li><a href="{{ route('dashboardUsers.index') }}" class="{{ request()->routeIs('dashboardUsers*') ? 'active' : 'inactive' }}">USERS</a></li>
        <li><a href="contact.asp" class="{{ request()->routeIs('dashboard.roles') ? 'active' : 'inactive' }}">ROLES</a></li>
      </ul>
</div>