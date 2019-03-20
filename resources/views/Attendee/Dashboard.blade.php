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
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @include('Public.ViewOrganiser.Partials.EventListingPanel',
                    [
                        'panel_title' => trans("Public_ViewOrganiser.upcoming_events"),
                        'events'      => $upcoming_events
                    ]
                )
            </div>
            <div class="col-xs-12 col-md-4">
                &nbsp;
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div style="text-align: center;">
                    {{ $upcoming_events->links()  }}
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                &nbsp;
            </div>
        </div>
    </section>
@endsection
