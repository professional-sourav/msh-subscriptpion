<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index() {
        $data = [
            'intent' => auth()->user()->createSetupIntent()
        ];

        return view('subscriptions.payment')->with($data);
    }

    public function store(Request $request) {
        
        $this->validate($request, [
            'token' => 'required',
            'plan'  => 'required'
        ]);

        $plan = Plans::where('identifier', strtolower( $request->plan ))
            ->firstOrFail();
        
        $request->user()
            ->newSubscription($plan->identifier, $plan->stripe_id)
            ->create($request->token);

        return redirect()->route("subscription.success");
    }
}
