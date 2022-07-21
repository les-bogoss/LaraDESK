<div class="navigation-container">
    <div class="content-bg"></div>

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
                <div class="avatar-dropdown-header-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </div>
                <div class="avatar-dropdown-header-email">{{ Auth::user()->email }}</div>
                <form action="{{route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="avatar-dropdown-header-button">Logout</button>
                </form>
            </div>
        </div>

    </div>
    @if (request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*'))
        @include('layouts.dashboard-navigation')
    @endif
    <!-- Logo -->
    <div class="logo">
        <a href="{{ route('tickets.index') }}">
            <img src="{{asset('favicon.ico')}}" alt="caca">
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

            @if(Auth::user()->hasPerm('read-data') || Auth::user()->hasPerm('read-user') || Auth::user()->hasPerm('read-role'))
                <div class="link">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-chart-line {{ request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active' : '' }}"></i>
                    </a>
                    <div class="{{ request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active-mark' : 'inactive-mark' }}"></div>
                </div>
            @endif

            <div class="link">
                <a href="{{ route('doc') }}">
                    <i class="fa-solid fa-file {{ request()->routeIs('doc') ? 'active' : '' }}"></i>
                </a>
                <div class="{{ request()->routeIs('doc') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>
        </div>


        <div class="settings">
            <a href="{{ route('account.index') }}">
                <i class="fa-solid fa-gear {{ request()->routeIs('account*') ? 'active' : '' }} " ></i>
            </a>
            <div class="{{ request()->routeIs('account*') ? 'active-mark' : 'inactive-mark' }}"></div>
        </div>
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

{{-- MOBILE NAVIGATION --}}

<div class="mobile-bg">
    
</div>

<div class="mobile-header">
    
<div class="mobile-dashboard-navigation">
    @if (request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*'))
        @include('layouts.dashboard-navigation')
    @endif
</div>

<button class="hamburger-menu" onclick="clickHandler()">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="mobile-navigation-container">


    <div class="avatar" onclick="switchState();">
        <img src="{{ Auth::user()->avatar }}" alt="">
    </div>
    <div class="avatar-dropdown">
        <div class="avatar-dropdown-header">
            <div class="avatar-dropdown-header-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </div>
            <div class="avatar-dropdown-header-email">{{ Auth::user()->email }}</div>
            <form action="{{route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="avatar-dropdown-header-button">Logout</button>
            </form>
        </div>
    </div>

    <div class="primary-navbar">


        <div class="links">

            <!-- Navigation Links -->
            <div class="link">
                <a href="{{ route('tickets.index') }}">
                    <i class="fa-solid fa-ticket-simple {{ request()->routeIs('tickets*') ? 'active' : '' }}"></i>
                    Tickets
                </a>
                <div class="{{ request()->routeIs('tickets*') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>

            @if(Auth::user()->hasPerm('read-data') || Auth::user()->hasPerm('read-user') || Auth::user()->hasPerm('read-role'))
                <div class="link">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-chart-line {{ request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active' : '' }}"></i>
                        Dashboard
                    </a>
                    <div class="{{ request()->routeIs('data*') || request()->routeIs('users*') || request()->routeIs('roles*') ? 'active-mark' : 'inactive-mark' }}"></div>
                </div>
            @endif

            <div class="link">
                <a href="{{ route('doc') }}">
                    <i class="fa-solid fa-file {{ request()->routeIs('doc') ? 'active' : '' }}"></i>
                    Documentation
                </a>
                <div class="{{ request()->routeIs('doc') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>

            <div class="settings">
                <a href="{{ route('account.index') }}">
                    <i class="fa-solid fa-gear {{ request()->routeIs('account*') ? 'active' : '' }} " ></i>
                    Settings
                </a>
                <div class="{{ request()->routeIs('account*') ? 'active-mark' : 'inactive-mark' }}"></div>
            </div>

        </div>
        
    </div>
    <form action="{{route('logout') }}" method="POST">
        @csrf
        <x-button type="submit" color="danger" class="avatar-dropdown-header-button">Logout</x-button>
    </form>
</div>
</div>


<script defer>
    let isOpen = false;
    let hamburgerElement = document.querySelector('.hamburger-menu');
    let navigationElement = document.querySelector('.mobile-navigation-container');
    let icon = document.querySelector('.hamburger-menu i');
    navigationElement.style.display = 'none'

    const clickHandler = () => {
        if(isOpen){
            navigationElement.style.display = 'none'
            isOpen = false;
            icon.classList.remove('fa-times')
            icon.classList.add('fa-bars')
        }else{
            navigationElement.style.display= 'flex'
            isOpen = true;
            icon.classList.remove('fa-bars')
            icon.classList.add('fa-times')
        }
    }
    
</script>


