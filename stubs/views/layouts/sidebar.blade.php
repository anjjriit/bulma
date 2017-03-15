{{-- Sidebar --}}
<aside class="menu app-sidebar animated">
    <p class="menu-label">
        MENU
    </p>
    <ul class="menu-list">
        <a @if(str_is('home', Route::current()->uri())) class="is-active" @endif href="{{ url('/home') }}">
            <span class="icon is-small"><i class="fa fa-home"></i></span>
            Home
        </a>

    </ul>
</aside>