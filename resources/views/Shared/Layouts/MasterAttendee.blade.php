@extends('Shared.Layouts.MasterBlank')

@section('title')
    @parent
    {{trans('Dashboard.home')}}
@endsection

@section('menu_title')
    {{Auth::user()->first_name}} {{Auth::user()->last_name}}
@endsection

@section('menu_body')
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{route('showUserEvents')}}">
                <i class="ico ico-home"></i>
                Home
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="{{route('showSelectOrganiser')}}" target="_blank">
                <i class="ico ico-external-link"></i>
                Organiser Dashboard
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a data-href="{{route('showEditUser')}}" data-modal-id="EditUser"
               class="loadModal editUserModal" href="javascript:void(0);"><span class="icon ico-user"></span>@lang("Top.my_profile")</a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="{{route('showUserTickets')}}">
                <i class="ico ico-calendar"></i>
                @lang('Top.my_tickets')
            </a>
        </li>
        <li class="divider"></li>
        <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
    </ul>
@endsection