@extends('Shared.Layouts.MasterAttendee')

@section('head')
    <style>
        @foreach($organisers as $organiser)
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('keyword', trans('Dashboard.keyword'), ['class' => 'control-label ']) !!}
                                {!! Form::text('keyword', Request::input('keyword'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group address-automatic">
                                {!! Form::label('venue_name_full', trans("Event.venue_name"), ['class' => 'control-label ']) !!}
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
                            {!! Form::label('location_radius', trans('Dashboard.distance'), ['class' => 'control-label']) !!}
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
                                        'data-field' => 'datetime',
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
                                        'data-field' => 'datetime',
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
                        </div>
                    </div>
                </form>
                <hr>
                @include('Public.ViewOrganiser.Partials.EventListingPanel',
                    [
                        'panel_title' => trans('Dashboard.search_results'),
                        'events'      => $upcoming_events
                    ]
                )
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
