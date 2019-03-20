<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showUserHome()
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        if (empty($user->roles)) {
            return response('User has no roles.', 500);
        }

        $isAdmin = false;
        $isAttendee = false;
        foreach ($user->roles as $role) {
            if ($role->name === 'administrator') {
                $isAdmin = true;
            }
            if ($role->name === 'attendee') {
                $isAttendee = true;
            }
        }

        if (!($isAdmin || $isAttendee)) {
            return response('User is not administrator or attendee.', 401);
        }

        if ($isAdmin) {
            return Redirect::route('showSelectOrganiser');
        }

        return Redirect::route('showUserEvents');
    }

    /**
     * Show the edit user modal
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showEditUser()
    {
        $user = Auth::user();
        $showWelcome = empty($user->first_name) || !$user->has_seen_first_modal;

        $user->has_seen_first_modal = true;
        $user->save();

        return view('ManageUser.Modals.EditUser', [
            'user' => $user,
            'showWelcome' => $showWelcome
        ]);
    }

    /**
     * Updates the current user
     *
     * @param Request $request
     * @return mixed
     */
    public function postEditUser(Request $request)
    {
        $rules = [
            'email'        => [
                'required',
                'email',
                'unique:users,email,' . Auth::user()->id . ',id,account_id,' . Auth::user()->account_id
            ],
            'password'     => 'passcheck',
            'new_password' => ['min:8', 'confirmed', 'required_with:password'],
            'first_name'   => ['required'],
            'last_name'    => ['required'],
        ];

        $messages = [
            'email.email'         => trans("Controllers.error.email.email"),
            'email.required'      => trans("Controllers.error.email.required"),
            'password.passcheck'  => trans("Controllers.error.password.passcheck"),
            'email.unique'        => trans("Controllers.error.email.unique"),
            'first_name.required' => trans("Controllers.error.first_name.required"),
            'last_name.required'  => trans("Controllers.error.last_name.required"),
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validation->messages()->toArray(),
            ]);
        }

        $user = Auth::user();

        if ($request->get('password')) {
            $user->password = Hash::make($request->get('new_password'));
        }

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');

        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.successfully_saved_details"),
        ]);
    }
}
