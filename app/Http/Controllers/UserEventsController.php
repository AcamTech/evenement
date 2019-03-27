<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTicket;
use App\Models\Attendee;
use App\Models\Category;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Config;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JavaScript;
use Log;
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

class UserEventsController extends MyBaseController
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
        $upcomingEvents = $query->orderBy('start_date', 'ASC')->get();

        if ($request->has('place_id') && !empty($request->input('place_id'))) {
            $rangeInMeters = $request->input('location_radius') * 1000;
            $inLat = $request->input('lat');
            $inLng = $request->input('lng');
            $upcomingEvents = array_filter(
                iterator_to_array($upcomingEvents),
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
        $upcomingEventOrganisers = [];
        foreach ($upcomingEvents as $event) {
            $upcomingEventOrganisers[$event->organiser->id] = $event->organiser;
        }

        // grouping events into pairs
        $eventGroups = [];
        foreach ($upcomingEvents as $i => $event) {
            $groupKey = ( $i - ( $i % 2 ) ) / 2;
            if (!array_key_exists($groupKey, $eventGroups)) {
                $eventGroups[$groupKey] = [];
            }
            $eventGroups[$groupKey][] = $event;
        }

        // gathering categories
        $categories = array_merge(
            ['-1' => trans('All')],
            iterator_to_array(Category::get()->pluck('name', 'id'))
        );

        // rendering it all out
        return view('Attendee.Dashboard', [
            'upcoming_events' => $upcomingEvents,
            'upcoming_event_groups' => $eventGroups,
            'upcoming_event_organisers' => $upcomingEventOrganisers,
            'categories' => $categories
        ]);
    }

    /**
     * @param $attendee_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showExportTicket($attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        Config::set('queue.default', 'sync');
        Log::info("*********");
        Log::info($attendee_id);
        Log::info($attendee);


        $this->dispatch(new GenerateTicket($attendee->order->order_reference . "-" . $attendee->reference_index));

        $pdf_file_name = $attendee->order->order_reference . '-' . $attendee->reference_index;
        $pdf_file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $pdf_file_name;
        $pdf_file = $pdf_file_path . '.pdf';


        return response()->download($pdf_file);
    }
}
