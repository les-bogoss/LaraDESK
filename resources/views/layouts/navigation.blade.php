<div class="navigation-container">

    <div class="layout-header">
        <div class="searchbar">
            <div class="icon">🔎</div>
            <input type="text" placeholder="Search for a ticket, a user or something else !">
        </div>

        <div class="avatar" onclick="switchState();">
            <img src="{{ Auth::user()->avatar }}" alt="">
        </div>
        <div class="avatar-dropdown">
            <div class="avatar-dropdown-header">
                <div class="avatar-dropdown-header-name">{{ Auth::user()->first_name }}</div>
                <div class="avatar-dropdown-header-email">{{ Auth::user()->email }}</div>
                <form action="{{route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="avatar-dropdown-header-button">Logout</button>
                </form>
            </div>
        </div>

    </div>
    @if (request()->routeIs('dashboard*') || request()->routeIs('users*') || request()->routeIs('roles*'))
        @include('layouts.dashboard-navigation')
    @endif
    <!-- Logo -->
    <div class="logo">
        <a href="{{ route('tickets.index') }}">
            <img src="https://cdn.discordapp.com/attachments/742748593500192869/986236599257923604/logo.png" alt="caca">
        </a>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="primary-navbar">

        <div class="links">

            <!-- Navigation Links -->
            <div class="link">
                <a href="{{ route('tickets.index') }}">
                    <i class="fa-solid fa-ticket-simple {{ request()->routeIs('tickets*') ? 'active' : '' }}"></i>
                </a>
                <div class="{{ request()->routeIs('tickets*') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>

            <div class="link">
                <a href="{{ route('dashboardData.index') }}">
                    <i class="fa-solid fa-chart-line {{ request()->routeIs('dashboard*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active' : '' }}"></i>
                </a>
                <div class="{{ request()->routeIs('dashboard*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>


            <div class="link">
                <a href="{{ route('dashboardData.index') }}">
                    <i class="fa-solid fa-file"></i>
                </a>
                <div class="{{ request()->routeIs('/') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>
        </div>


        <div class="settings">
            <a href="{{ route('dashboardData.index') }}">
                <i class="fa-solid fa-gear"></i>
            </a>
            <div class="{{ request()->routeIs('/') ? 'active-mark' : 'inactive-mark' }}"></div>
        </div>
    </div>
    <script>
        let state = "hidden"
        let drop  = document.querySelector('.avatar-dropdown')
        function switchState(){
            if(state == "hidden"){
                state = "visible"
                drop.style.display = "flex"
            }else{
                state = "hidden"
                drop.style.display = "none"
            }
        }
    </script>
</div>
