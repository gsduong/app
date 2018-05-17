<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ContactInfoController extends Controller
{
	private $user;
	private $restaurant;

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
            $this->restaurant = $this->user->restaurants->where('slug', '=', $request->route('slug'))->first();
            if (!$this->restaurant) {
            	return redirect()->route('restaurant.index')->with('error', 'You can not access this restaurant!');
            }
            return $next($request);
        });
    }

    public function index() {
    	return view('restaurant/contact/index', ['restaurant' => $this->restaurant]);
    }

    public function update(Request $request, $slug) {
        $id = $request->id;
        if ($id) {
            $contact = $this->restaurant->contacts->find($id);
            if ($contact) {
                $contact->address = $request->address;
                $contact->phone = $request->phone;
                $contact->secondary_phone = $request->secondary_phone;
                $contact->map_url = $request->map_url;
                $contact->save();
                return redirect()->route('contact.index', $slug)->with('success', 'Contact successfully updated!');
            }
            return redirect()->route('contact.index', $slug)->with('error', 'Contact not found!');
        }
        return redirect()->route('contact.index', $slug)->with('error', 'Contact not found!');
    }

    public function create(Request $request, $slug) {
    	$address = $request->address;
        $phone = $request->phone;
        $secondary_phone = $request->secondary_phone;
        $map_url = $request->map_url;
        $this->restaurant->contacts()->create(['address' => $address, 'phone' => $phone, 'secondary_phone' => $secondary_phone, 'map_url' => $map_url ]);
        return redirect()->route('contact.index', $slug)->with('success', 'Contact successfully created!');
    }

    public function delete ($slug, $contact_id) {
        $contact = $this->restaurant->contacts->find($contact_id);
        if ($contact) {
            $contact->delete();
            return redirect()->route('contact.index', $slug)->with('success', 'Contact successfully deleted!');
        }
        return redirect()->route('contact.index', $slug)->with('error', 'Contact not found!');
    }
}
