<div class="layout-dashboard-header">
    <ul>
        @if (Auth::user()->hasPerm('read-data'))
        <li><a href="{{ route('data.index') }}"
                class="{{ request()->routeIs('data*') ? 'active' : 'inactive' }}">DATA</a></li>
        @endif
        @if (Auth::user()->hasPerm('read-user'))
            <li><a href="{{ route('users.index') }}"
                    class="{{ request()->routeIs('users*') ? 'active' : 'inactive' }}">USERS</a></li>
        @endif
        @if (Auth::user()->hasPerm('read-role'))
            <li><a href="{{ route('roles.index') }}"
                    class="{{ request()->routeIs('roles*') ? 'active' : 'inactive' }}">ROLES</a></li>
        @endif
    </ul>
</div>
