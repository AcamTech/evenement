<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JavaScript;
use Validator;

class UserEventsController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function showEvents(Request $request)
    {
        $user = Auth::user();
        $user->has_seen_first_modal = !$user->has_seen_first_modal;
        $user->save();

        if ($request->isMethod('post')) {
            $query = Event::where('end_date', '>=', Carbon::now());
            if ($request->has('keyword')) {
                $query = $query->where('title', 'like', '%'. $request->input('keyword'). '%');
            }
            if ($request->has('place_id')) {
                $query = $query->where('location_google_place_id', $request->input('place_id'));
            }
            $upcoming_events = $query->paginate(10);
        } else {
            $upcoming_events = Event::where('end_date', '>=', Carbon::now())->paginate(10);
        }

        $organisers = [];
        foreach ($upcoming_events as $event) {
            $organisers[$event->organiser->id] = $event->organiser;
        }

        JavaScript::put([
            'User'                => [
                'full_name'    => Auth::user()->full_name,
                'email'        => Auth::user()->email,
                'is_confirmed' => Auth::user()->is_confirmed,
            ],
            'DateTimeFormat'      => config('attendize.default_date_picker_format'),
            'DateSeparator'       => config('attendize.default_date_picker_seperator'),
            'GenericErrorMessage' => trans("Controllers.whoops"),
        ]);

        return view('Attendee.Dashboard', [
            'upcoming_events' => $upcoming_events,
            'organisers' => $organisers
        ]);
    }
}
