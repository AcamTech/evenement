@extends('Shared.Layouts.MasterAttendee')

@section('content')
    <p>Hello, world!!!</p>
    <p>Attendees: {{count($attendees)}}</p>
    <ul>
        @foreach($attendees as $attendee)
            <li>{{$attendee->email}}</li>
        @endforeach
    </ul>
@endsection
