<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Organiser;
use Illuminate\Http\Request;
use Log;

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
     * @param int $organiser_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateCategory(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);

        $category = Category::createNew();

        if (!$category->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $category->errors()
            ]);
        }

        $category->title = $request->input('title');
        $category->organiser_id = $organiser->id;

        try {
            $category->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => trans("Controllers.category_create_exception"),
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $category->id,
            'redirectUrl' => route('showOrganiserCategories', [
                'organiser_id'  => $organiser->id
            ]),
        ]);
    }
}
