<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public function verify(Request $request) {
	    if ($request->input("hub_mode") === "subscribe"
	        && $request->input("hub_verify_token") === env("WEBHOOK_VERIFY_TOKEN")) {
	        return response($request->input("hub_challenge"), 200);
	    }
	}

	public function receive(Request $request) {
        $data = $request->all();
        dd($data);
	}
}
