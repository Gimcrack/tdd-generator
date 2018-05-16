<nav class="navbar navbar-expand-lg navbar-custom">
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a href="{{ url('/') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-fw fa-user"></i>
                            {{ Auth::user()->name }}
                            @if( Auth::user()->isAdmin() )
                            <span class="badge badge-info">Admin</span>
                            @endif
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#"
                                    onclick="event.preventDefault();
                                             Bus.$emit('ShowPasswordForm')">
                                    Reset Password
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>



                @endif
            </ul>
            @auth
                <button onclick="Bus.$emit('ToggleRight')" :class="{active : showRightMenu, animate : animateChat}" class="btn btn-info btn-sm ml-2">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            @endauth
        </div>
</nav>
