<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InoOicClient\Flow\Basic;
use InoOicClient\Flow\Exception\TokenRequestException;
use Redirect;
use View;

class GCCollabLoginController extends Controller
{
    protected $redirectTo = '/';

    /**
     * @var Basic
     */
    protected $oidc;

    /**
     * @var string
     */
    protected $defaultPassword;

    public function __construct()
    {
        $this->oidc = new Basic([
            'client_info' => [
                'client_id' => config('services.gccollab.client_id'),
                'redirect_uri' => config('services.gccollab.callback'),

                'authorization_endpoint' => 'https://account.gccollab.ca/openid/authorize',
                'token_endpoint' => 'https://account.gccollab.ca/openid/token',
                'user_info_endpoint' => 'https://account.gccollab.ca/openid/userinfo',

                'authentication_info' => [
                    'method' => 'client_secret_post',
                    'params' => ['client_secret' => config('services.gccollab.client_secret')]
                ]
            ]
        ]);
        $this->defaultPassword = config('services.gccollab.default_password');
    }

    private function getAuthorizationRequestUri()
    {
        return $this->oidc->getAuthorizationRequestUri('openid email profile');
    }

    /**
     * Shows login form.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect(Route('showUserHome'));
        }

        // redirecting on plain request
        if (empty($request->query('code'))) {
            return redirect($this->getAuthorizationRequestUri());
        }

        // attempting to gather user info
        try {
            $userInfo = $this->oidc->process();
        } catch (TokenRequestException $e) {
            // handling invalid tokens provided by manually loading url, reauthenticating
            return redirect($this->getAuthorizationRequestUri());
        } catch (\Exception $e) {
            // handling any other exception
            return response('', 500)->json(['error' => $e->getMessage()]);
        }

        // gathering global account
        $globalAccount = Account::query()->where('id', 1)->first();
        if (is_null($globalAccount)) {
            return response('', 500)->json(['error' => 'Global account does not exist']);
        }

        // gathering attendee role
        $attendeeRole = Role::query()->where('name', 'attendee')->first();
        if (is_null($attendeeRole)) {
            return response('', 500)->json(['error' => 'Attendee role does not exist']);
        }

        // checking to see whether the user exists and creating where necessary
        $gcCollabUserId = $userInfo['sub'];
        $gcCollabEmail = $userInfo['email'];
        $gcCollabName = $userInfo['name'];
        $gcCollabUsername = $userInfo['nickname'];

        $foundUser = User::query()->where('email', $gcCollabEmail)->first();
        if (is_null($foundUser)) {
            $firstName = $gcCollabName;
            $lastName = '';
            if (strpos($gcCollabName, ' ') > -1) {
                $nameParts = explode(' ', $gcCollabName, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1];
            }

            $foundUser = User::create([
                'email' => $gcCollabEmail,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => Hash::make($this->defaultPassword),
                'account_id' => $globalAccount->id,
                'is_parent' => 0,
                'is_registered' => 1
            ]);
            $foundUser->save();

            $foundUser->roles()->attach($attendeeRole->id);

            session()->flash('message', 'Success! You can now login.');
        }
        Auth::login($foundUser, true);

        return redirect($this->redirectTo);
    }
}
