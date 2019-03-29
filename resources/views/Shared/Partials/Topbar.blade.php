<header id="header" class="navbar">

    <div class="navbar-header">
        <a class="navbar-brand" href="{{route('showUserHome')}}">
            <img
                style="width: auto; height: 34px; margin-left: 15px;"
                class="logo"
                alt="ISED Event Manager"
                src="{{asset('assets/images/sig-blk-en.svg')}}"
            />
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

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                @if(Lang::locale() === 'en')
                    <a class='text underlined' href="/fr/{{Request::path()}}">Français</a>
                @elseif(Lang::locale() === 'fr')
                    <a class='text underlined' href="/{{sprintf('en/%s', substr(Request::path(), strlen('fr/')))}}">English</a>
                @endif
            </li>
        </ul>
    </div>
</header>