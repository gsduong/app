<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    protected $redirectTo = User::AUTH_REDIRECT;
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        }
        catch (\Exception $e) {
            return redirect('/');
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->getId())->first();

        if ($authUser) {
        	$authUser->name = $user->getName();
        	$authUser->email = $user->getEmail();
        	$authUser->token = $user->token;
        	$authUser->avatar = $user->avatar;
        	$authUser->save();
            return $authUser;
        }

        return User::create([
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'avatar'    => $user->avatar,
            'password'	=> bcrypt(str_random(25)),
            'provider' => $provider,
            'provider_id' => $user->getId(),
            'token'	=> $user->token
        ]);
    }
}
