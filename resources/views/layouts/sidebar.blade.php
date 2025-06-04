<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">BizzGrow</div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="emoji-icon">&#128202;</span>
                Dashboard
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('penjualan') }}"> 
                <span class="emoji-icon">&#128722;</span>
                Penjualan
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="#"> 
                <span class="emoji-icon">&#128302;</span> 
                Prediksi
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                <span class="emoji-icon">&#128100;</span>
                Akun
            </a>
        </li>
    </ul>

    <button class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="emoji-icon">&#128682;</span> 
        Logout
    </button>
</aside>
