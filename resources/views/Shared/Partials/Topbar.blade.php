<header id="header" class="navbar">

    <div class="navbar-header">
        <a class="navbar-brand" href="javascript:void(0);">
            <img style="width: 150px;" class="logo" alt="Attendize" src="{{asset('assets/images/logo-light.png')}}"/>
        </a>
    </div>

    <div class="navbar-toolbar clearfix">
        @yield('top_nav')

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown profile">

                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="meta">
                        <span class="text ">
                            {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                        </span>
                        <span class="arrow"></span>
                    </span>
                </a>

                @include('Shared.Partials.Menu')
            </li>
        </ul>
    </div>
</header>