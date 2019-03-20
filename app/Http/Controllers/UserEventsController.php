<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\EventStats;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Validator;

class UserEventsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function showEvents()
    {
        return view('Attendee.Dashboard');
    }
}
