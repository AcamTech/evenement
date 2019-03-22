@extends('Shared.Layouts.MasterBlank')

@section('menu_title')
    {{isset($organiser->name) ? $organiser->name : $event->organiser->name}}
@endsection

@section('menu_body')
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{route('showCreateOrganiser')}}">
                <i class="ico ico-plus"></i>
                @lang("Top.create_organiser")
            </a>
        </li>
        @foreach($organisers as $org)
            <li>
                <a href="{{route('showOrganiserDashboard', ['organiser_id' => $org->id])}}">
                    <i class="ico ico-building"></i> &nbsp;
                    {{$org->name}}
                </a>

            </li>
        @endforeach
        <li class="divider"></li>
        <li>
            <a href="{{route('showUserEvents')}}" target="_blank">
                <i class="ico ico-external-link"></i>
                Your Attendee Dashboard
            </a>
        </li>
        <li class="divider"></li>

        <li>
            <a data-href="{{route('showEditUser')}}" data-modal-id="EditUser"
               class="loadModal editUserModal" href="javascript:void(0);"><span class="icon ico-user"></span>@lang("Top.my_profile")</a>
        </li>
        <li class="divider"></li>
        <li><a data-href="{{route('showEditAccount')}}" data-modal-id="EditAccount" class="loadModal"
               href="javascript:void(0);"><span class="icon ico-cog"></span>@lang("Top.account_settings")</a></li>


        <li class="divider"></li>
        <li><a target="_blank" href="https://github.com/Attendize/Attendize/issues/new?body=Version%20{{ config('attendize.version') }}"><span class="icon ico-megaphone"></span>@lang("Top.feedback_bug_report")</a></li>
        <li class="divider"></li>
        <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
    </ul>
@endsection