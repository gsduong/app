<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Restaurant;
use Facebook;
class FacebookController extends Controller
{
  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/r';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('guest')->except('logout');
  }

  public function showFormLogin() {
  	$login_url = Facebook::getLoginUrl(['email', 'manage_pages', 'pages_messaging']);
  	return view('auth/facebook/login', ['login_url' => $login_url]);
  }

  public function handleFacebookCallback() {
    try {
      $user_access_token = Facebook::getAccessTokenFromRedirect();

      $request = Facebook::get('/me?fields=permissions,id,name,email,picture', $user_access_token);
      $user = $request->getGraphUser();
      $permissions = $user['permissions'];

      // if user denied ANY of the required permissions
      foreach ($permissions as $p => $permission) 
      {
        if ($permission['status'] !== 'granted')
        {
          $re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email', 'pages_messaging']);
          return view('auth/facebook/login', ['error' => 'We require all permissions in order to connect your Facebook pages', 'login_url' => $re_login_url]);
        }
      }
      // log user in
      $authUser = $this->findOrCreateUser($user, $user_access_token);
      Auth::login($authUser);
      return redirect($this->redirectTo)->with('success', 'Successfully Signed In!');
    }
    catch(Facebook\Exceptions\FacebookResponseException $e) {
      $re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email', 'pages_messaging']);
      return view('auth/facebook/login', ['error' => $e->getMessage(), 'login_url' => $re_login_url]);
    }
    catch (Facebook\Exceptions\FacebookSDKException $e) {
      $re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email', 'pages_messaging']);
      return view('auth/facebook/login', ['error' => $e->getMessage(), 'login_url' => $re_login_url]);
    }
  }

  public function logout(Request $request){
    Auth::logout();
    return redirect('/')->with('success', 'Successfully Signed Out!');;
  }

  public function test() {
      // return url('/facebook/login');
      return view('restaurant/test', ['error' => 'Error!']);
  }

  /**
  * If a user has registered before using social auth, return the user
  * else, create a new user object.
  */
  private function findOrCreateUser($graph_user, $user_access_token)
  {

    // get the list of pages that user is admin of

    $authUser = User::where('provider_id', $graph_user['id'])->first();
    if ($authUser) {
      // update name, email, token, profile picture
      $authUser->name = $graph_user['name'];
      $authUser->email = $graph_user['email'];
      $authUser->user_access_token = $user_access_token;
      $authUser->avatar = User::get_profile_picture_url($graph_user['id'], "normal");
      $authUser->save();
      $pages = $authUser->update_pages();
      return $authUser;
    }

    // if user does not exist, then create a new one
    $user = User::create([
      'name'     => $graph_user['name'],
      'email'    => $graph_user['email'],
      'avatar'    => User::get_profile_picture_url($graph_user['id'], "normal"),
      'password'  => bcrypt(str_random(25)),
      'provider' => "facebook",
      'provider_id' => $graph_user['id'],
      'user_access_token' => $user_access_token,
    ]);
    $user->update_pages();
    return $user;
  }
}
