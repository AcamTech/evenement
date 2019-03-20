<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Validator;

class UserEventsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function showEvents()
    {
        $upcoming_events = Event::where('end_date', '>=', Carbon::now())->paginate(15);

        $organisers = [];
        foreach ($upcoming_events as $event) {
            $organisers[$event->organiser->id] = $event->organiser;
        }

        return view('Attendee.Dashboard', [
            'upcoming_events' => $upcoming_events,
            'organisers' => $organisers
        ]);
    }
}
