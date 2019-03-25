@extends('Shared.Layouts.MasterBlank')

@section('title')
    @parent
    {{trans('Dashboard.home')}}
@endsection

@section('menu_title')
    {{Auth::user()->first_name}} {{Auth::user()->last_name}}
@endsection