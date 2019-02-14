<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return redirect('/wew-lad');
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

        // checking to see whether the user exists and creating where necessary
        $gcCollabUserId = $userInfo['sub'];
        $gcCollabEmail = $userInfo['email'];
        $gcCollabName = $userInfo['name'];
        $gcCollabUsername = $userInfo['nickname'];

        $foundUser = User::query()->where('email', $gcCollabEmail)->first();
        if (is_null($foundUser)) {
            $foundUser = User::create([
                'name' => $gcCollabName,
                'email' => $gcCollabEmail,
                'password' => $this->defaultPassword
            ]);
            $foundUser->save();
        }
        Auth::login($foundUser, true);

        return redirect($this->redirectTo);
    }
}
