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

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversineGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

class UserEventsController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function showEvents(Request $request)
    {
        // querying for events
        $query = Event::where('end_date', '>=', Carbon::now())
            ->where('is_live', 1);
        if ($request->isMethod('post')) {
            if ($request->has('keyword') && !empty($request->input('keyword'))) {
                $query = $query->where('title', 'like', '%'. $request->input('keyword'). '%');
            }
            if ($request->has('start_date') && !empty($request->input('start_date'))) {
                $query = $query->where('start_date', '>=', date('Y-m-d h:i:sA', strtotime($request->input('start_date'))));
            }
            if ($request->has('end_date') && !empty($request->input('end_date'))) {
                $query = $query->where('end_date', '<=', date('Y-m-d h:i:sA', strtotime($request->input('end_date'))));
            }
        }
        $upcoming_events = $query->orderBy('start_date', 'ASC')->get();

        if ($request->has('place_id') && !empty($request->input('place_id'))) {
            $rangeInMeters = $request->input('location_radius') * 1000;
            $inLat = $request->input('lat');
            $inLng = $request->input('lng');
            $upcoming_events = array_filter(
                iterator_to_array($upcoming_events),
                function (Event $event) use ($rangeInMeters, $inLat, $inLng) {
                    if (empty($event->location_lat) || empty($event->location_long)) {
                        return false;
                    }

                    $eventDistanceInMeters = haversineGreatCircleDistance($inLat, $inLng,  $event->location_lat, $event->location_long);
                    return $eventDistanceInMeters < $rangeInMeters;
                }
            );
        }

        // resolving organisers
        $organisers = [];
        foreach ($upcoming_events as $event) {
            $organisers[$event->organiser->id] = $event->organiser;
        }

        // putting js necessary for geocomplete querying
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

        // grouping events into pairs
        $eventGroups = [];
        foreach ($upcoming_events as $i => $event) {
            $groupKey = ( $i - ( $i % 2 ) ) / 2;
            if (!array_key_exists($groupKey, $eventGroups)) {
                $eventGroups[$groupKey] = [];
            }
            $eventGroups[$groupKey][] = $event;
        }

        // rendering it all out
        return view('Attendee.Dashboard', [
            'upcoming_events' => $upcoming_events,
            'event_groups' => $eventGroups,
            'organisers' => $organisers
        ]);
    }
}
