@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang("Organiser.organiser_categories")
@stop

@section('page_title')
    @lang("Organiser.organiser_name_categories", ["name"=>$organiser->name])
@stop

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop

@section('head')
    {!! HTML::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.env("GOOGLE_MAPS_GEOCODING_KEY")) !!}
    {!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('content')
    <p>Hello, world!</p>
@stop
