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
                <form action="" class="gf">
                    <div class="row">
                        <div class="col-sm-12">
                            {!! Form::label('name', trans('Dashboard.keyword'), ['class' => 'control-label ']) !!}
                            <input
                                type="text"
                                name="keyword"
                                class="form-control"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group address-automatic">
                                {!! Form::label('name', trans("Event.venue_name"), ['class' => 'control-label ']) !!}
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
                                    {!! Form::hidden('formatted_address', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('street_number', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('country_short', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('place_id', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('name', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('location', '', ['class' => 'location_field']) !!}
                                    {!! Form::hidden('postal_code', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('route', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lat', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('lng', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('administrative_area_level_1', null, ['class' => 'location_field']) !!}
                                    {!! Form::hidden('sublocality', '', ['class' => 'location_field']) !!}
                                    {!! Form::hidden('locality', null, ['class' => 'location_field']) !!}
                                </div>
                                <!-- /These are populated with the Google places info-->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('name', trans('Dashboard.distance'), ['class' => 'control-label']) !!}
                            <select name="location_radius" id="" class="form-control">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('start_date', trans("Event.event_start_date"), ['class' => 'control-label']) !!}
                                {!! Form::text(
                                    'start_date',
                                    null,
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
                                    null,
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
                </form>
                @include('Public.ViewOrganiser.Partials.EventListingPanel',
                    [
                        'panel_title' => trans("Public_ViewOrganiser.upcoming_events"),
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
