<aside class="sidebar sidebar-left sidebar-menu">
    <section class="content">
        <h5 class="heading">@lang("Organiser.organiser_menu")</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserDashboard', ['organiser_id' => $organiser->id])}}">
                    <span class="figure"><i class="ico-home2"></i></span>
                    <span class="text">@lang("Organiser.dashboard")</span>
                </a>
            </li>
            <li class="{{ Request::is('*events*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserEvents', ['organiser_id' => $organiser->id])}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event")</span>
                </a>
            </li>

            <li class="{{ Request::is('*categories*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserCategories', ['organiser_id' => $organiser->id])}}">
                    <span class="figure"><i class="ico-cabinet"></i></span>
                    <span class="text">@lang("Organiser.categories")</span>
                </a>
            </li>

            <li class="{{ Request::is('*customize*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserCustomize', ['organiser_id' => $organiser->id])}}">
                    <span class="figure"><i class="ico-cog"></i></span>
                    <span class="text">@lang("Organiser.customize")</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
