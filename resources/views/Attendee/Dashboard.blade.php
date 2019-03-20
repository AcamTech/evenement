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

    <script>
      $(function() {
        // try {
        //   $(".geocomplete").geocomplete({
        //     details: "form.gf",
        //     types: ["geocode", "establishment"]
        //   }).bind("geocode:result", function(event, result) {
        //     console.log(result);
        //   }, 1000);
        //
        // } catch (e) {
        //   console.log(e);
        // }

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
            <div class="col-xs-12 col-md-8">
                <form action="">
                    <input type="text" name="keyword">
                    <input type="text" name="location">
                    <select name="location_radius" id="">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('start_date', trans("Event.event_start_date"), ['class'=>'required control-label']) !!}
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
                                {!! Form::label('end_date', trans("Event.event_end_date"), ['class'=>'required control-label']) !!}
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
