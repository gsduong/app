<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook;
use Validator;
use Bitly;
use Cloudder;
use App\Restaurant;
use App\User;
class RestaurantController extends Controller
{
	private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->user->update_pages();
        $restaurants = $this->user->restaurants;
        return view('restaurant/index', ['restaurants' => $restaurants]);
    }

    public function selectPage() {
        // get the list of pages
        $this->user->update_pages();
        $pages = json_decode($this->user->pages, true);
        return view('restaurant/select-page', ['pages' => $pages]);
    }

    public function create(Request $request) {
        $page_id = $request->fb_page_id;
        if (Restaurant::where('fb_page_id', '=', $page_id)->first()) {
            $this->user->update_pages();
            return redirect('/r')->with('error', 'Restaurant with the given page id already exists!');
        }
        if ($this->user->is_admin_of_page($page_id)) {
            $user_id = $this->user->id;
            $image_url = null;
            $public_id = null;
            if ($request->hasFile('image_file')) {
                $result = $this->uploadImage($request);
                if (!$result) {
                    return redirect()->route('restaurant.show-form-create')->withError('Only accept jpeg,bmp,jpg,png files! Size is upto 6000kb!');
                }
                $image_url = $result['url'];
                $public_id = $result['public_id'];
            }
            try {
                // do your database transaction here
                $restaurant = $this->user->restaurants()->create([
                    'name' => $request->name,
                    'fb_page_id' => $page_id,
                    'fb_page_access_token' => $this->user->get_page_access_token_from_page_id($page_id),
                    'creator_id' => $user_id,
                    'avatar' => User::get_page_picture_url_from_page_id($page_id),
                    'background_url' => $image_url
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('/r')->with('error', 'Cannot create new restaurant with the given page id!');
            } catch (\Exception $e) {
                return redirect('/r')->with('error', 'Cannot create new restaurant with the given page id!');
            }

            // update user's pages
            $this->user->update_pages();
            return redirect('/r')->with('success', 'Successfully created new restaurant!');
        }
        return redirect('/r')->with('error', 'Cannot create new restaurant with the given page id!');
    }

    public function showFormCreate() {
        return view('restaurant/create');
    }

    public function showFormCreateWithId(Request $request) {
        $page_id = $request->page_id;
        if ($page = $this->user->get_page_from_page_id($page_id)) {
            return view('restaurant/create', ['page' => $page]);
        }
        return redirect()->route('restaurant.select-page')->with('error' , 'Page not found');
    }

    public function delete($restaurant_id) {
        $restaurant = $this->user->restaurants->find($restaurant_id);
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->with('error', 'Item not found!');
        }
        if ($this->user->is_admin_of_page($restaurant->fb_page_id)) {
            $restaurant->delete();
            // $admins = $restaurant->admins();
            // foreach ($admins as $user) {
            //     $user->update_pages();
            // }
            return redirect()->route('restaurant.index')->with('success', 'Item deleted!');
        }
        return redirect()->route('restaurant.index')->with('error', 'Unauthorized!');
    }

    public function show($slug) {
        $restaurant = $this->user->restaurants->where('slug', $slug)->first();
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->with('error', 'Unauthorized!');
        }
        $this->updatePageAccessToken($slug);
        $restaurant->update_users();
        return view('restaurant/restaurant', ['restaurant' => $restaurant]);
    }

    public function staff_index($slug){
        $restaurant = $this->user->restaurants->where('slug', $slug)->first();
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->with('error', 'Unauthorized!');
        }
        $restaurant->update_users();
        return view('restaurant/staff/staff', ['restaurant' => $restaurant]);
    }

    private function uploadImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'image_file'=>'mimes:jpeg,bmp,jpg,png|between:1, 6000'
        ]);
        if ($request->hasFile('image_file')) {
            if ($validator->fails()) {
                return null;
            }
            // upload and get image_url
            $file = $request->image_file;
            //upload image
            Cloudder::upload($file, 'booknow/' . $file, ['type' => 'upload']);
            // $image_url = Cloudder::getResult()['url'];
            // dd($image_url);
            $public_id = Cloudder::getPublicId();
            list($width, $height) = getimagesize($file);
            $image_url= Cloudder::show('booknow/' . $file, ["width" => $width, "height"=>$height]);
            return ['url' => $image_url, 'public_id' => $public_id];

        } else return null;
    }

    public function showFormEdit($restaurant_id) {
        $restaurant = $this->user->restaurants->find($restaurant_id);
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->withError('Unauthorized!');
        }
        return view('restaurant/edit', ['restaurant' => $restaurant]);
    }

    public function update(Request $request, $restaurant_id) {
        $restaurant = $this->user->restaurants->find($restaurant_id);
        if (!$restaurant) {
            return redirect()->route('restaurant.index')->withError('Unauthorized!');
        }
        $page_id = $restaurant->fb_page_id;
        if ($this->user->is_admin_of_page($page_id)) {
            $user_id = $this->user->id;
            $image_url = null;
            $public_id = null;
            $old_url = $restaurant->background_url;
            if ($request->hasFile('image_file')) {
                $result = $this->uploadImage($request);
                if (!$result) {
                    return redirect()->route('restaurant.show-form-create')->withError('Only accept jpeg,bmp,jpg,png files! Size is upto 6000kb!');
                }
                $image_url = $result['url'];
                $public_id = $result['public_id'];
            }
            try {
                // do your database transaction here
                $restaurant->update([
                    'name' => $request->name,
                    'fb_page_id' => $page_id,
                    'fb_page_access_token' => $this->user->get_page_access_token_from_page_id($page_id),
                    'creator_id' => $user_id,
                    'avatar' => User::get_page_picture_url_from_page_id($page_id),
                    'background_url' => $image_url ? $image_url : $old_url
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('/r')->with('error', 'Cannot update this time. Try again later!');
            } catch (\Exception $e) {
                return redirect('/r')->with('error', 'Cannot update this time. Try again later!');
            }

            // update user's pages
            $this->user->update_pages();
            return redirect('/r')->with('success', 'Successfully updated restaurant!');
        }
        return redirect()->route('restaurant.index')->withError('Unauthorized!');
    }

    private function updatePageAccessToken($slug){
        $restaurant = $this->user->restaurants->where('slug', $slug)->first();
        if (!$this->user->is_admin_of_page($restaurant->fb_page_id)) {
            return;
        }
        $response = null;
        try {
            // Returns a `FacebookFacebookResponse` object
            $response = Facebook::get("/" . $restaurant->fb_page_id . "?fields=access_token", $this->user->user_access_token);
            file_put_contents("php://stderr", "Updating page_access_token for page_id " . $restaurant->fb_page_id);
        } catch(\Exception $e) {
            file_put_contents("php://stderr", $e->getMessage());
        }
        $result = $response->getGraphObject()->asArray();
        if ($result) {
            if(array_key_exists("access_token", $result)) {
                $restaurant->fb_page_access_token = $result["access_token"];
                $restaurant->save();
                file_put_contents("php://stderr", "page_access_token updated - page_id " . $restaurant->fb_page_id);
            }
        }
        return;
    }
}
