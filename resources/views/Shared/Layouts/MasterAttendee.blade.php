@extends('Shared.Layouts.MasterBlank')

@section('sidebar-gate')
    no-sidebar
@endsection

@section('title')
    @parent
    {{trans('Dashboard.home')}}
@endsection