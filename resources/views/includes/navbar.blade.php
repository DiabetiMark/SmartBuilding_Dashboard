<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="http://bulma.io">
            <img src="{{ asset('img/logo.png') }}" alt="SmartBuilding Aareon" width="112" height="28">
        </a>
        <a class="navbar-item is-hidden-desktop" href="#" target="_blank">
        </a>
        <div class="navbar-burger burger" data-target="navMenubd-example">
            <span>1</span>
            <span>2</span>
            <span>3</span>
        </div>
    </div>
    <div id="navMenubd-example" class="navbar-menu">
        <div class="navbar-end">
            <a class="navbar-item" href="#">
                <i class="far fa-user"></i>&nbsp;Account
            </a>
            <a class="navbar-item" href="{{ url('/login') }}">
                <i class="fas fa-sign-out-alt"></i>&nbsp;Uitloggen
            </a>
        </div>
    </div>
</nav>
<div class="columns is-fullheight">
    <div class="column is-2 is-sidebar-menu is-hidden-mobile">
        <aside class="menu">
            <p class="menu-label"><i class="fas fa-server"></i>&nbsp;Ruimtes</p>
            <ul class="menu-list">
                <li><a href="{{ url('/') }}" {{ (Request::is('/') ? 'class=is-active' : '') }}>Overzicht</a></li>
                <li>
                    <ul>
                        <li><a>TODO</a></li>
                    </ul>
                </li>
            </ul>
            <p class="menu-label"><i class="fas fa-cogs"></i>&nbsp;Beheer</p>
            <ul class="menu-list">
                <li><a href="{{ url('/settings') }}" {{ (Request::is('settings') ? 'class=is-active' : '') }}>Instellingen</a></li>
                <li><a href="{{ url('/users') }}" {{ (Request::is('users') ? 'class=is-active' : '') }}>Gebruikers</a></li>
            </ul>
        </aside>
    </div>
    <div class="column is-main-content">
        @yield('content')
    </div>
</div>