<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index() {
        $plans = Plans::get();

        return view('subscriptions.plans', compact('plans'));
    }    

    public function cancel(Request $request) {

        $this->validate($request, [
            'plan'  => 'required'
        ]);

        auth()->user()->subscription($request->plan)->cancelNow();

        return redirect()->route("subscription.canceled");
    }

    public function canceled(Request $request) {

        return view('subscriptions.canceled');
    }

    public function success(Request $request) {

        return view('subscriptions.success');
    }
}
