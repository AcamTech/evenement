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
                <form action="{{route('postUserEvents')}}" method="post" class="gf">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            {!! Form::label('keyword', trans('Dashboard.keyword'), ['class' => 'control-label ']) !!}
                            {!! Form::text('keyword', Input::old('keyword'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group address-automatic">
                                {!! Form::label('venue_name_full', trans("Event.venue_name"), ['class' => 'control-label ']) !!}
                                {!! Form::text(
                                    'venue_name_full',
                                    Input::old('venue_name_full'),
                                    [
                                        'class' => 'form-control geocomplete location_field',
                                        'placeholder' => trans("Event.venue_name_placeholder")//'E.g: The Crab Shack'
                                    ]
                                ) !!}

                                <!--These are populated with the Google places info-->
                                <div>
                                    {!! Form::hidden('formatted_address', Input::old('formatted_address'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('street_number', Input::old('street_number'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country', Input::old('country'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country_short', Input::old('country_short'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('place_id', Input::old('place_id'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('name', Input::old('name'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('location', Input::old('location'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('postal_code', Input::old('postal_code'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('route', Input::old('route'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lat', Input::old('lat'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lng', Input::old('lng'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('administrative_area_level_1', Input::old('administrative_area_level_1'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('sublocality', Input::old('sublocality'), ['class' => 'location_field']) !!}
                                    {!! Form::hidden('locality', Input::old('locality'), ['class' => 'location_field']) !!}
                                </div>
                                <!-- /These are populated with the Google places info-->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('location_radius', trans('Dashboard.distance'), ['class' => 'control-label']) !!}
                            {!! Form::select(
                                'location_radius',
                                [
                                    '5' => '5',
                                    '5' => '5',
                                    '5' => '5'
                                ],
                                Input::old('location_radius'),
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
                                    Input::old('start_date'),
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
                                    Input::old('end_date'),
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
                        'panel_title' => 'Search Results',
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
