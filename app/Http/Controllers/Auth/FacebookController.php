<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Facebook;
class FacebookController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

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
    	$login_url = Facebook::getLoginUrl(['email', 'manage_pages']);
    	return view('auth/facebook/login', ['login_url' => $login_url]);
    }

    public function handleFacebookCallback() {
        try {
			$token = Facebook::getAccessTokenFromRedirect();

			$request = Facebook::get('/me?fields=permissions,id,name,email,picture', $token);
			$user = $request->getGraphUser();
			$permissions = $user['permissions'];

			// if user denied ANY of the required permissions
			foreach ($permissions as $p => $permission) 
			{
			    if ($permission['status'] !== 'granted')
			    {
                    $re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email']);
                    return view('auth/facebook/login', ['error' => 'We require all permissions in order to connect your Facebook pages', 'login_url' => $re_login_url]);
			    }
			}
            $authUser = $this->findOrCreateUser($user, $token);
            Auth::login($authUser);
			return redirect($this->redirectTo)->with('success', 'Successfully Signed In!');
        }
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            $re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email']);
            return view('auth/facebook/login', ['error' => $e->getMessage(), 'login_url' => $re_login_url]);
        }
        catch (Facebook\Exceptions\FacebookSDKException $e) {
        	$re_login_url = Facebook::getReRequestUrl(['manage_pages', 'email']);
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
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    private function findOrCreateUser($graph_user, $access_token)
    {
        // dd($graph_user["id"]);
        /*
        GraphUser {#230 ▼
          #items: array:4 [▼
            "permissions" => GraphEdge {#229 ▼
              #request: FacebookRequest {#218 ▶}
              #metaData: []
              #parentEdgeEndpoint: "/1250104828454382/permissions"
              #subclassName: null
              #items: array:4 [▼
                0 => GraphNode {#225 ▼
                  #items: array:2 [▼
                    "permission" => "email"
                    "status" => "granted"
                  ]
                }
                1 => GraphNode {#226 ▼
                  #items: array:2 [▼
                    "permission" => "manage_pages"
                    "status" => "granted"
                  ]
                }
                2 => GraphNode {#227 ▼
                  #items: array:2 [▼
                    "permission" => "pages_show_list"
                    "status" => "granted"
                  ]
                }
                3 => GraphNode {#228 ▼
                  #items: array:2 [▼
                    "permission" => "public_profile"
                    "status" => "granted"
                  ]
                }
              ]
            }
            "id" => "1250104828454382"
            "name" => "Gs Dương"
            "email" => "nguyenduong.ict.hust@gmail.com"
          ]
        }
        */
        $authUser = User::where('provider_id', $graph_user['id'])->first();

        if ($authUser) {
            // update name, email, token, profile picture
            $authUser->name = $graph_user['name'];
            $authUser->email = $graph_user['email'];
            $authUser->token = $access_token;
            // ignore password
            // ignore provider
            // ignore id: $authUser->provider_id = $graph_user->id;
            $authUser->avatar = $this->get_profile_picture_url($graph_user['id'], "normal");
            $authUser->save();
            return $authUser;
        }

        // if user does not exist, then create a new one
        return User::create([
            'name'     => $graph_user['name'],
            'email'    => $graph_user['email'],
            'avatar'    => $this->get_profile_picture_url($graph_user['id'], "normal"),
            'password'  => bcrypt(str_random(25)),
            'provider' => "facebook",
            'provider_id' => $graph_user['id'],
            'token' => $access_token
        ]);
    }

    private function get_profile_picture_url($id, $type="normal"){
        // $type = normal | square | null
        return 'http://graph.facebook.com/' . $id. '/picture?type=' . $type;
    }
}
