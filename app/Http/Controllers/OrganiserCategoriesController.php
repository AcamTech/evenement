<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Organiser;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
            'categories' => $organiser->categories,
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
            'modal_id' => $request->get('modal_id'),
            'organisers' => Organiser::scope()->pluck('name', 'id'),
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

        $category = new Category();

        if (!$category->validate($request->all())) {
            return response()->json([
                'status' => 'error',
                'messages' => $category->errors()
            ]);
        }

        $category->name = $request->input('name');
        $category->organiser_id = $organiser->id;

        try {
            $category->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'messages' => trans("Controllers.category_create_exception"),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'id' => $category->id,
            'redirectUrl' => route('showOrganiserCategories', [
                'organiser_id' => $organiser->id
            ]),
        ]);
    }


    /**
     * Delete a category
     *
     * @param Request $request
     * @param $organiser_id
     * @param $category_id
     * @return Redirector
     */
    public function deleteCategory(Request $request, $organiser_id, $category_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);
        $category = Category::findOrFail($category_id);

        if ($category->belongsTo($organiser)){
            if (count($category->events) <= 0){
                // this actually deletes it
                $category->forceDelete();
            }else{
                // this is just a soft delete, since there are events that are using this category
                $category->destroy($category_id);
            }
        }

        return redirect()->route('showOrganiserCategories', $organiser->id);
    }


    /**
     * Show the 'Edit Category' Modal
     *
     * @param Request $request
     * @param int $organiser_id
     * @param int $category_id
     * @return \Illuminate\View\View
     */
    public function showEditCategory(Request $request, $organiser_id, $category_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);
        $category = Category::findOrFail($category_id);

        return view('ManageOrganiser.Modals.EditCategory', [
            'modal_id' => $request->get('modal_id'),
            'organisers' => Organiser::scope()->pluck('name', 'id'),
            'organiser' => $organiser,
            'category' => $category
        ]);
    }

    /**
     * Edit a category
     *
     * @param Request $request
     * @param int $organiser_id
     * @param int category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditCategory(Request $request, $organiser_id, $category_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);

        $category = Category::findOrFail($category_id);

        if (!$category->validate($request->all())) {
            return response()->json([
                'status' => 'error',
                'messages' => $category->errors()
            ]);
        }

        $category->name = $request->input('name');

        try {
            $category->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'messages' => trans("Controllers.category_create_exception"),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'id' => $category->id,
            'redirectUrl' => route('showOrganiserCategories', [
                'organiser_id' => $organiser->id
            ]),
        ]);
    }
}
