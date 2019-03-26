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
    /**
     * Show the 'Create Category' Modal
     *
     * @param Request $request
     * @param int $organiser_id
     * @return \Illuminate\View\View
     */
    public function showCreateCategory(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);

        return view('ManageOrganiser.Modals.CreateCategory', [
            'modal_id'     => $request->get('modal_id'),
            'organisers'   => Organiser::scope()->pluck('name', 'id'),
            'organiser' => $organiser,
        ]);
    }

    /**
     * Create an event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateCategory(Request $request)
    {
        return response()->json(['m' => 'Hello, world!']);
    }
}
