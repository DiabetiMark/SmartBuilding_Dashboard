<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="http://bulma.io">
            <img src="{{ asset('img/logo.png') }}" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
        </a>
        <a class="navbar-item is-hidden-desktop" href="https://github.com/jgthms/bulma" target="_blank">
      <span class="icon" style="color: #333;">
      <i class="fa fa-github"></i>
      </span>
        </a>
        <a class="navbar-item is-hidden-desktop" href="https://twitter.com/jgthms" target="_blank">
      <span class="icon" style="color: #55acee;">
      <i class="fa fa-twitter"></i>
      </span>
        </a>
        <div class="navbar-burger burger" data-target="navMenubd-example">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div id="navMenubd-example" class="navbar-menu">
        <div class="navbar-end">
            <a class="navbar-item" href="#">
                <i class="far fa-user"></i>&nbsp;Account
            </a>
            <a class="navbar-item" href="#">
                <i class="fas fa-sign-out-alt"></i>&nbsp;Uitloggen
            </a>
        </div>
    </div>
</nav>
<div class="columns is-fullheight">
    <div class="column is-2 is-sidebar-menu is-hidden-mobile">
        <aside class="menu">
            <p class="menu-label">Ruimtes</p>
            <ul class="menu-list">
                <li><a class="is-active">Overzicht</a></li>
                <li>
                    <ul>
                        <li><a>Ruimte 1</a></li>
                        <li><a>Ruimte 2</a></li>
                        <li><a>Ruimte 3</a></li>
                    </ul>
                </li>
            </ul>
            <p class="menu-label">Beheer</p>
            <ul class="menu-list">
                <li><a>Instellingen</a></li>
                <li><a>Gebruikers</a></li>
            </ul>
        </aside>
    </div>
    <div class="column is-main-content">
        @yield('content')
    </div>
</div>