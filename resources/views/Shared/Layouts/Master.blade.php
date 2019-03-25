@extends('Shared.Layouts.MasterBlank')

@section('menu_title')
    {{isset($organiser->name) ? $organiser->name : $event->organiser->name}}
@endsection