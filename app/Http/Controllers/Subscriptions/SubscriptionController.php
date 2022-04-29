<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index() {

        $plans = Plans::whereNotIn("identifier", auth()->user()->subscriptions()->active()->get()->pluck("name"))
            ->get();

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

    /**
     * http://msh-subscription.loc/activate?activation_callback=
     */
    public function activatePlugin(Request $request)
    {
        $site_url = base64_decode( $request->activation_callback );

        $user = null;

        if ( Auth::check() ) {

            $user = Auth::user();

        } else {

            $credentials      = [
                "email"     => config("main.default.login.email"),
                "password"  => config("main.default.login.password")
            ];

            if ( Auth::attempt( $credentials ) ) {

                $user = Auth::user();
            }
        }

        if ( !is_null( $user ) ) {

            // Log::info( auth()->user()->subscriptions()->active()->plan->get() );

            foreach ( auth()->user()->subscriptions()->active()->get() as $stripeSubscription ) {

                $subscription = new Subscription( json_decode( $stripeSubscription, true ) );

                Log::info($subscription->plan);

                return redirect()->route("activation.success")->with([
                    "plugin"                => true,
                    "plan"                  => base64_encode($subscription->plan->id),
                    "subscription"          => base64_encode($subscription->id),
                    "activation_callback"   => $request->activation_callback
                ]);
            }
        }
    }


    // "admin.php?page=bertha-ai-license&bertha_success_response=YTdlZTIxNzM0NGNhYzgyZjA4ZDIwZDMyY2JhMDc1ZDk%3D&bertha_key_expires=bGlmZXRpbWU%3D",
    public function activationSuccess(Request $request, StripeService $stripe)
    {
        $url = sprintf(
            "%s/admin.php?page=bertha-ai-license&bertha_success_response=%s&bertha_key_expires=%s",
            $request->activation_callback,
            $request->subscription,
            ""
        );

        return view("plugin.success", compact( "url" ));
    }
}
