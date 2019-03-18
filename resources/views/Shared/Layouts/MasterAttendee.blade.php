@extends('Shared.Layouts.MasterBlank')

@section('menu_title')
    TODO
@endsection

@section('menu_body')
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="#">
                <i class="ico ico-plus"></i>
                TODO
            </a>
        </li>
        <li class="divider"></li>
        <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
    </ul>
@endsection