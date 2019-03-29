@extends('Shared.Layouts.MasterAttendee')


@section('head')
    <style>
        @foreach($upcoming_event_organisers as $organiser)
            .event-list > li.event-item-{{$organiser->id}} > time {
            color: {{$organiser->page_text_color}};
            background-color: {{$organiser->page_header_bg_color}};
        }
        @endforeach

        .address-manual {
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
            margin-bottom: 10px;
            background-color: #FAFAFA;
        }
    </style>

    {!! HTML::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.env("GOOGLE_MAPS_GEOCODING_KEY")) !!}
    {!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js') !!}
    <script>
      $(function() {
        try {
          $(".geocomplete").geocomplete({
            details: "form.gf",
            types: ["geocode", "establishment"]
          }).bind("geocode:result", function(event, result) {
            console.log(result);
          }, 1000);

        } catch (e) {
          console.log(e);
        }

        $("#DatePicker").remove();
        var $div = $("<div>", {id: "DatePicker"});
        $("body").append($div);
        $div.DateTimePicker({
          dateTimeFormat: window.Attendize.DateTimeFormat,
          dateSeparator: window.Attendize.DateSeparator
        });

      });
    </script>
@stop

@section('content')
    <section id="events" class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{{trans('Dashboard.browse_events')}}</h2>
                <h4>{{trans('Dashboard.search_form')}}</h4>
                <form action="{{route('postUserEvents')}}" method="post" class="gf address-manual">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('keyword', trans('Dashboard.keyword'), ['class' => 'control-label ']) !!}
                                {!! Form::text('keyword', Request::input('keyword'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('category_id', trans("Event.event_category"), array('class'=>'control-label required')) !!}
                                {!! Form::select('category_id', $categories, Request::input('category_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group address-automatic">
                                {!! Form::label('venue_name_full', trans("Dashboard.location"), ['class' => 'control-label ']) !!}
                                {!! Form::text(
                                    'venue_name_full',
                                    Request::input('venue_name_full'),
                                    [
                                        'class' => 'form-control geocomplete location_field',
                                        'placeholder' => trans("Event.venue_name_placeholder")//'E.g: The Crab Shack'
                                    ]
                                ) !!}

                                <!--These are populated with the Google places info-->
                                <div>
                                    {!! Form::hidden('formatted_address', Request::input('formatted_address'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('street_number', Request::input('street_number'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country', Request::input('country'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country_short', Request::input('country_short'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('place_id', Request::input('place_id'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('name', Request::input('name'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('location', Request::input('location'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('postal_code', Request::input('postal_code'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('route', Request::input('route'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lat', Request::input('lat'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lng', Request::input('lng'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('administrative_area_level_1', Request::input('administrative_area_level_1'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('sublocality', Request::input('sublocality'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('locality', Request::input('locality'), ['class' => 'location_field']) !!}
                                </div>
                                <!-- /These are populated with the Google places info-->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('location_radius', trans('Dashboard.distance_from'), ['class' => 'control-label']) !!}
                            {!! Form::select(
                                'location_radius',
                                [
                                    '5' => '5 KM',
                                    '10' => '10 KM',
                                    '25' => '25 KM'
                                ],
                                Request::input('location_radius'),
                                ['class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('start_date', trans("Event.event_start_date"), ['class' => 'control-label']) !!}
                                {!! Form::text(
                                    'start_date',
                                    Request::input('start_date'),
                                    [
                                        'class' => 'form-control start hasDatepicker ',
                                        'data-field' => 'date',
                                        'data-startend' => 'start',
                                        'data-startendelem' => '.end',
                                        'readonly' => ''
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group">
                                {!! Form::label('end_date', trans("Event.event_end_date"), ['class' => 'control-label']) !!}
                                {!! Form::text(
                                    'end_date',
                                    Request::input('end_date'),
                                    [
                                        'class' => 'form-control end hasDatepicker ',
                                        'data-field' => 'date',
                                        'data-startend' => 'end',
                                        'data-startendelem' => '.start',
                                        'readonly' => ''
                                    ]
                                ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('showUserHome')}}" class="btn btn-link">{{trans('Dashboard.reset')}}</a>
                        </div>
                    </div>
                </form>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h1 class="event-listing-heading">{{ trans('Dashboard.search_results') }}</h1>
                            @if(empty($upcoming_event_groups))
                                <div class="alert alert-info">
                                    @lang("Public_ViewOrganiser.no_events", ["panel_title"=>trans('Dashboard.search_results')])
                                </div>
                            @else
                                @foreach($upcoming_event_groups as $eventGroup)
                                    <div class="row">
                                        @foreach($eventGroup as $event)
                                            <div class="col-md-6">
                                                <ul class="event-list">
                                                    <li class="event-item-{{$event->organiser->id}}">
                                                        <time datetime="{{ $event->start_date }}">
                                                            <span class="day">{{ $event->start_date->format('d') }}</span>
                                                            <span class="month">{{ explode("|", trans("basic.months_short"))[$event->start_date->format('n')] }}</span>
                                                            <span class="year">{{ $event->start_date->format('Y') }}</span>
                                                            <span class="time">{{ $event->start_date->format('h:i') }}</span>
                                                        </time>
                                                        @if(count($event->images))
                                                            <img class="hide" alt="{{ $event->title }}" src="{{ asset($event->images->first()['image_path']) }}"/>
                                                        @endif
                                                        <div class="info">
                                                            <h2 class="title ellipsis">
                                                                <a href="{{$event->event_url }}">{{ $event->title }}</a>
                                                            </h2>
                                                            <p class="desc ellipsis">{{ $event->venue_name }}</p>

                                                            @if($event->category)
                                                            <p class="desc ellipsis">{{ $event->category->name }}</p>
                                                            @endif

                                                            <ul>
                                                                <li style="width:50%;"><a href="{{$event->event_url }}">@lang("Public_ViewOrganiser.tickets")</a></li>
                                                                <li style="width:50%;"><a href="{{$event->event_url }}">@lang("Public_ViewOrganiser.information")</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <?php /*
                <div style="text-align: center;">
                    {{ $upcoming_events->links()  }}
                </div>
                */ ?>
            </div>
            <div class="col-xs-12 col-md-4">
                &nbsp;
            </div>
        </div>
    </section>
@endsection
