<header>
    <div class="container-fluid position-relative no-side-padding">

        <a href="#" class="logo">Blog</a>

        <div class="menu-nav-icon" data-nav-menu="#main-menu"><i class="ion-navicon"></i></div>

        <ul class="main-menu visible-on-click" id="main-menu">
            <li><a href="{{ route('welcome') }}">Home</a></li>
            <li><a href="{{ route('all.post') }}">Posts</a></li>
            @guest
            <li><a href="{{ route('login') }}">Login</a></li>
                @else
               @if (Auth::user()->role_id == 1)
               <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
               @endif
               @if (Auth::user()->role_id == 2)
               <li><a href="{{ route('author.dashboard.index') }}">Dashboard</a></li>
               @endif
            @endguest

        </ul><!-- main-menu -->

        <div class="src-area">
            <form action="{{ route('search') }}" method="POST">
                @csrf
                <button class="src-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                <input class="src-input" value="{{ isset($query) ? $query : '' }}" name="query"type="text" placeholder="Type of search">
            </form>
        </div>

    </div><!-- conatiner -->
</header>
