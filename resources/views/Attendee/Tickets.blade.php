@extends('Shared.Layouts.MasterAttendee')

@section('content')
    <p>Hello, world!!!</p>
    <p>Attendees: {{count($attendees)}}</p>
    <ul>
        @foreach($attendees as $attendee)
            <li>{{$attendee->email}}</li>
        @endforeach
    </ul>
    <!--Start Attendees table-->
    <div class="row">
        <div class="col-md-12">
            @if(!$attendees->count())
                <p>You are not attending any events!</p>
            @else
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
                            @foreach($attendees as $attendee)
                                <?php

                                $event = $attendee->event;

                                ?>
                                <tr class="attendee_{{$attendee->id}} {{$attendee->is_cancelled ? 'danger' : ''}}">
                                    <td>{{{$attendee->full_name}}}</td>
                                    <td>
                                        <a data-modal-id="MessageAttendee" href="javascript:void(0);" class="loadModal"
                                           data-href="{{route('showMessageAttendee', ['attendee_id'=>$attendee->id])}}"
                                        > {{$attendee->email}}</a>
                                    </td>
                                    <td>
                                        {{{$attendee->ticket->title}}}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" data-modal-id="view-order-{{ $attendee->order->id }}" data-href="{{route('showManageOrder', ['order_id'=>$attendee->order->id])}}" title="View Order #{{$attendee->order->order_reference}}" class="loadModal">
                                            {{$attendee->order->order_reference}}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang("basic.action") <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a
                                                        href="{{route('showExportTicket', ['event_id'=>$event->id, 'attendee_id'=>$attendee->id])}}"
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
            @endif
        </div>
    </div>    <!--/End attendees table-->
@endsection
