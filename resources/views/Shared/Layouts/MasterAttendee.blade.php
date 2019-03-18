@extends('Shared.Layouts.MasterBlank')

@section('title')
    @parent
    @lang('basic.home')
@endsection

@section('page_title')
    <i class="ico-home2"></i>
    @lang('basic.dashboard')
@stop

@section('menu_title')
    {{Auth::user()->first_name}} {{Auth::user()->last_name}}
@endsection

@section('menu_body')
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{route('showUserHome')}}">
                <i class="ico ico-home"></i>
                @lang('basic.home')
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a data-href="{{route('showEditUser')}}" data-modal-id="EditUser"
               class="loadModal editUserModal" href="javascript:void(0);"><span class="icon ico-user"></span>@lang("Top.my_profile")</a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <i class="ico ico-calendar"></i>
                @lang('basic.my_events')
            </a>
        </li>
        <li class="divider"></li>
        <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
    </ul>
@endsection