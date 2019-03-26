<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\EventStats;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Validator;

class UserAttendeesController extends MyBaseController
{
    /**
     * @return Factory|View
     */
    public function showUserTickets()
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        $attendees = $user->attendees()
            ->join('orders', 'orders.id', '=', 'attendees.order_id')
            ->join('events', 'events.id', '=', 'attendees.event_id')
            ->withoutCancelled()
            ->orderBy('events.start_date', 'desc')
            ->select('attendees.*', 'orders.order_reference')
            ->get();

        $eventAttendees = [];
        foreach ($attendees as $attendee) {
            $eventId = $attendee->event->id;
            if (!array_key_exists($eventId, $eventAttendees)) {
                $eventAttendees[$eventId] = [
                    'event' => $attendee->event,
                    'attendees' => []
                ];
            }
            $eventAttendees[$eventId]['attendees'][] = $attendee;
        }

        return view('Attendee.Tickets', [
            'attendees' => $attendees,
            'event_attendees' => $eventAttendees
        ]);
    }

    /**
     * Shows the 'Cancel Attendee' modal
     *
     * @param Request $request
     * @param $attendee_id
     * @return View|Factory
     */
    public function showCancelAttendee(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
            'event'    => $attendee->event,
            'tickets'  => $attendee->event->tickets->pluck('title', 'id'),
        ];

        return view('Attendee.Modals.CancelAttendee', $data);
    }

    /**
     * Cancels an attendee
     *
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function postCancelAttendee(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $error_message = false; //Prevent "variable doesn't exist" error message

        if ($attendee->is_cancelled) {
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.attendee_already_cancelled"),
            ]);
        }

        $attendee->ticket->decrement('quantity_sold');
        $attendee->ticket->decrement('sales_volume', $attendee->ticket->price);
        $attendee->ticket->event->decrement('sales_volume', $attendee->ticket->price);
        $attendee->is_cancelled = 1;
        $attendee->save();

        $eventStats = EventStats::where('event_id', $attendee->event_id)->where('date', $attendee->created_at->format('Y-m-d'))->first();
        if($eventStats){
            $eventStats->decrement('tickets_sold',  1);
            $eventStats->decrement('sales_volume',  $attendee->ticket->price);
        }

        $data = [
            'attendee'   => $attendee,
            'email_logo' => $attendee->event->organiser->full_logo_path,
        ];

        if ($request->get('notify_attendee') == '1') {
            Mail::send('Emails.notifyCancelledAttendee', $data, function ($message) use ($attendee) {
                $message->to($attendee->email, $attendee->full_name)
                    ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                    ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->subject(trans("Email.your_ticket_cancelled"));
            });
        }

        if ($request->get('refund_attendee') == '1') {

            try {
                // This does not account for an increased/decreased ticket price
                // after the original purchase.
                $refund_amount = $attendee->ticket->price;
                $data['refund_amount'] = $refund_amount;

                $gateway = Omnipay::create($attendee->order->payment_gateway->name);

                // Only works for stripe
                $gateway->initialize($attendee->order->account->getGateway($attendee->order->payment_gateway->id)->config);

                $request = $gateway->refund([
                    'transactionReference' => $attendee->order->transaction_id,
                    'amount'               => $refund_amount,
                    'refundApplicationFee' => false,
                ]);

                $response = $request->send();

                if ($response->isSuccessful()) {

                    // Update the attendee and their order
                    $attendee->is_refunded = 1;
                    $attendee->order->is_partially_refunded = 1;
                    $attendee->order->amount_refunded += $refund_amount;

                    $attendee->order->save();
                    $attendee->save();

                    // Let the user know that they have received a refund.
                    Mail::send('Emails.notifyRefundedAttendee', $data, function ($message) use ($attendee) {
                        $message->to($attendee->email, $attendee->full_name)
                            ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                            ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                            ->subject(trans("Email.refund_from_name", ["name"=>$attendee->event->organiser->name]));
                    });
                } else {
                    $error_message = $response->getMessage();
                }

            } catch (\Exception $e) {
                \Log::error($e);
                $error_message = trans("Controllers.refund_exception");

            }
        }

        if ($error_message) {
            return response()->json([
                'status'  => 'error',
                'message' => $error_message,
            ]);
        }

        session()->flash('message', trans("Controllers.successfully_cancelled_attendee"));

        return response()->json([
            'status'      => 'success',
            'id'          => $attendee->id,
            'redirectUrl' => '',
        ]);
    }
}
