@extends('Shared.Layouts.MasterAttendee')

@section('title')
    @parent
    -
    {{trans('Dashboard.my_events')}}
@endsection

@section('page_title')
    <div class="container">
        {{trans('Dashboard.my_events')}}
    </div>
@stop

@section('content')
    <div class="container">
        <h2>{{trans('Dashboard.my_events')}}</h2>
        <!--Start Attendees table-->
        <div class="row">
            <div class="col-md-12">
                @if(empty($event_attendees))
                    <p>{{trans('Dashboard.not_attending')}}</p>
                @else
                    @foreach($event_attendees as $eventId => $eventData)
                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{$eventData['event']->title}}</h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="pull-right">
                                    <small>{{date('M j g:ia', strtotime($eventData['event']->start_date))}} &ndash; {{date('M j g:ia', strtotime($eventData['event']->end_date))}}</small>
                                </h4>
                            </div>
                        </div>
                        <p><a href="{{route('showEventPage', ['event_id' => $eventId])}}">{{trans('Dashboard.view_event_details')}}</a></p>
                        <div class="panel">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            @lang('Attendee.name')
                                        </th>
                                        <th>
                                            @lang('Attendee.email')
                                        </th>
                                        <th>
                                            @lang('ManageEvent.ticket')
                                        </th>
                                        <th>
                                            @lang('Order.order_ref')
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($eventData['attendees'] as $attendee)
                                        <?php

                                        $event = $attendee->event;

                                        ?>
                                        <tr class="attendee_{{$attendee->id}} {{$attendee->is_cancelled ? 'danger' : ''}}">
                                            <td>{{{$attendee->full_name}}}</td>
                                            <td>
                                                {{$attendee->email}}
                                            </td>
                                            <td>
                                                {{{$attendee->ticket->title}}}
                                            </td>
                                            <td>
                                                {{$attendee->order->order_reference}}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang("basic.action") <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a
                                                                    href="{{route('userShowExportTicket', ['attendee_id'=>$attendee->id])}}"
                                                                    href="#"
                                                            >
                                                                @lang("ManageEvent.download_pdf_ticket")
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <a
                                                        data-modal-id="CancelAttendee"
                                                        href="javascript:void(0);"
                                                        data-href="{{route('userShowCancelAttendee', ['attendee_id'=>$attendee->id])}}"
                                                        class="loadModal btn btn-xs btn-danger"
                                                >
                                                    @lang("basic.cancel")
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>    <!--/End attendees table-->
    </div>
@endsection
