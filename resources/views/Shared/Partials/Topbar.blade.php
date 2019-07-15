<header id="header" class="navbar">

    <div class="navbar-header">
        <div class="navbar-brand">
            <p class="frontend-logo">
                <img
                    style="width: 372px; height: 34px; margin-left: 15px;"
                    class="logo"
                    alt="ISED Event Manager"
                    src="{{asset('assets/images/sig-blk-en.svg')}}"
                />
            </p>
            <p class="application-logo">
                <img
                    style="width: 150px;"
                    class="logo"
                    alt="ISED Event Manager"
                    src="{{asset('assets/images/logo-light.png')}}"
                />
            </p>
        </div>
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
                    <a class='text underlined' href="{{URL::to('/fr/')}}{{Request::path()}}">Français</a>
                @elseif(Lang::locale() === 'fr')
                    <a class='text underlined' href="{{URL::to('/')}}{{sprintf('en/%s', substr(Request::path(), strlen('fr/')))}}">English</a>
                @endif
            </li>
        </ul>
    </div>
</header>