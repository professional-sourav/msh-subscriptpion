<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\PaymentService;
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

        $plan = Plan::where('identifier', strtolower( $request->plan ))
            ->firstOrFail();
        
        $request->user()
            ->newSubscription($plan->identifier, $plan->stripe_id)
            ->create($request->token);

        return redirect()->route("subscription.success");
    }

    public function validatePlan(Request $request) {

        if ( !empty( $request->identifier ) ) {

            $paymentService = new PaymentService();
            
            return response()->json([
                "status" => $paymentService->validatePlanByIdentifier( $request->identifier )
            ]);
        }

        return response()->json([
            "status" => false
        ]);
    }
}
