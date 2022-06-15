<div class="navigation-container">

    <div class="layout-header">
        <div class="searchbar">
            <div class="icon">🔎</div>
            <input type="text" placeholder="Search for a ticket, a user or something else !">
        </div>

        <div class="avatar">
            <img src="{{ Auth::user()->avatar }}" alt="">
        </div>
    </div>
    @if (request()->routeIs('dashboard*'))
        @include('layouts.dashboard-navigation')
    @endif
    <!-- Logo -->
    <div class="logo">
        <a href="{{ route('tickets') }}">
            <img src="https://cdn.discordapp.com/attachments/742748593500192869/986236599257923604/logo.png" alt="caca">
        </a>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="primary-navbar">

        <div class="links">

            <!-- Navigation Links -->
            <div class="link">
                <a href="{{ route('tickets') }}">
                    {{ __('📜') }}
                </a>
                <div class="{{ request()->routeIs('tickets') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>

            <div class="link">
                <a href="{{ route('dashboardData.index') }}">
                    {{ __('🐔') }}
                </a>
                <div class="{{ request()->routeIs('dashboard*') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>


            <div class="link">
                <a href="{{ route('dashboardData.index') }}">
                    {{ __('⌚') }}
                </a>
                <div class="{{ request()->routeIs('/') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>
        </div>


        <div class="settings">
            <a href="{{ route('dashboardData.index') }}">
                {{ __('⚙️') }}
            </a>
            <div class="{{ request()->routeIs('/') ? 'active-mark' : 'inactive-mark' }}"></div>
        </div>
    </div>
</div>
