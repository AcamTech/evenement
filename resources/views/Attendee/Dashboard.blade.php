@extends('Shared.Layouts.MasterAttendee')

@section('head')
    <style>
        @foreach($organisers as $organiser)
            .event-list > li.event-item-{{$organiser->id}} > time {
            color: {{$organiser->page_text_color}};
            background-color: {{$organiser->page_header_bg_color}};
        }
        @endforeach

    </style>
@stop

@section('content')
    <section id="events" class="container">
        <p>Hello, world!</p>
        <p>Upcoming events: {{count($upcoming_events)}}</p>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @include('Public.ViewOrganiser.Partials.EventListingPanel',
                    [
                        'panel_title' => trans("Public_ViewOrganiser.upcoming_events"),
                        'events'      => $upcoming_events
                    ]
                )
                @include('Public.ViewOrganiser.Partials.EventListingPanel',
                    [
                        'panel_title' => trans("Public_ViewOrganiser.past_events"),
                        'events'      => $past_events
                    ]
                )
            </div>
            <div class="col-xs-12 col-md-4">
                &nbsp;
            </div>
        </div>
    </section>
@endsection
