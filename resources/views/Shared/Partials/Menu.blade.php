<ul class="dropdown-menu" role="menu">
    @if(Auth::user() && Auth::user()->isAttendee())
        <li>
            <a href="{{route('showUserEvents')}}">
                <i class="ico ico-search"></i>
                {{trans('Top.events_search')}}
            </a>
        </li>
        <li class="divider"></li>
    @endif

    @if(Auth::user() && Auth::user()->isAdmin())
        <li>
            <a href="{{route('showCreateOrganiser')}}">
                <i class="ico ico-plus"></i>
                @lang("Top.create_organiser")
            </a>
        </li>
        @if($organisers)
            @foreach($organisers as $org)
                <li>
                    <a href="{{route('showOrganiserDashboard', ['organiser_id' => $org->id])}}">
                        <i class="ico ico-building"></i> &nbsp;
                        @if(isset($organiser) && $org->id === $organiser->id)
                            <strong>{{$org->name}}</strong>
                        @else
                            {{$org->name}}
                        @endif
                    </a>

                </li>
            @endforeach
        @endif
        <li class="divider"></li>
    @endif

    <li>
        <a data-href="{{route('showEditUser')}}" data-modal-id="EditUser"
           class="loadModal editUserModal" href="javascript:void(0);"><span class="icon ico-user"></span>@lang("Top.my_profile")</a>
    </li>
    <li class="divider"></li>

    @if(Auth::user() && Auth::user()->isAttendee())
        <li>
            <a href="{{route('showUserTickets')}}">
                <i class="ico ico-calendar"></i>
                @lang('Top.my_tickets')
            </a>
        </li>
        <li class="divider"></li>
    @endif

    @if(Auth::user() && Auth::user()->isAdmin())
        <li>
            <a
                data-href="{{route('showEditAccount')}}"
                data-modal-id="EditAccount"
                class="loadModal"
                href="javascript:void(0);"
            >
                <span class="icon ico-cog"></span>@lang("Top.account_settings")
            </a>
        </li>
        <li class="divider"></li>
    @endif

    <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
</ul>