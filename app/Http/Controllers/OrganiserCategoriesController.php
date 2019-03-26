<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use Illuminate\Http\Request;

class OrganiserCategoriesController extends MyBaseController
{
    /**
     * Show the organiser categories page
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function showCategories(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);


        $data = [
            'categories'    => $organiser->categories,
            'organiser' => $organiser,
        ];

        return view('ManageOrganiser.Categories', $data);
    }
}
