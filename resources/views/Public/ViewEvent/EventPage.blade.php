@extends('Public.ViewEvent.Layouts.EventPage')

@section('menu_title')
    {{Auth::user()->first_name}} {{Auth::user()->last_name}}
@endsection

@section('content')
    @include('Public.ViewEvent.Partials.EventHeaderSection')
    @include('Public.ViewEvent.Partials.EventTicketsSection')
    @include('Public.ViewEvent.Partials.EventDescriptionSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventMapSection')
    @include('Public.ViewEvent.Partials.EventOrganiserSection')
    @include('Public.ViewEvent.Partials.EventFooterSection')
@stop

