<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index() {

        $plans = Plan::whereNotIn("identifier", auth()->user()->subscriptions()->active()->get()->pluck("name"))
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

            $subscriptions = auth()->user()->subscriptions();

            if ( $subscriptions->count() > 0 ) {

                // if there are multiple subscription exists, take the latest one
                $latestActiveSubscription = $subscriptions->active()->latest()->first();

                if ( !is_null( $latestActiveSubscription ) ) {

                    return redirect()->route("activation.success", [
                        "plugin"                => true,
                        "subscription"          => base64_encode( $latestActiveSubscription->id ),
                        "activation_callback"   => $request->activation_callback
                    ]);
                }
            }

            return redirect()->route("activation.failed", [
                "plugin"                => true,
                "activation_callback"   => $request->activation_callback
            ]);
        }
    }


    public function activationSuccess(Request $request, StripeService $stripe)
    {
        $subscription = auth()->user()
            ->subscriptions()
            ->find(
                base64_decode( $request->subscription ) 
            );

        if ( !is_null( $subscription ) ) {

            $stripeSubscription = $stripe->getSubscription( $subscription->stripe_id );

            // echo json_encode($stripeSubscription); die;
            // echo Carbon::parse( $stripeSubscription["current_period_end"] )->toDateTimeString(); die;

            $subscription_end_at = Carbon::parse( $stripeSubscription["current_period_end"] )->toDateString();

            $url = sprintf(
                "%s/wp-admin/admin.php?page=bertha-ai-license&bertha_success_response=%s&bertha_key_expires=%s",
                base64_decode($request->activation_callback),
                base64_encode($subscription->id),
                base64_encode( $subscription_end_at )
            );

            $url = base64_encode( $url );

            return view("plugin.success", compact( "url", "subscription" ));
        }
    }


    public function activationFailed(Request $request)
    {
        return view("plugin.failed");
    }

    public function postActivationSuccess(Request $request)
    {
        if ( $request->has( '_activate_plugin' ) ) {

            // get the plugin redirect URL
            $plugin_redirect_url = base64_decode( $request->input('_activate_plugin') );

            // get the subscription
            $active_subscription_id = base64_decode(
                $request->input( '_active_subscription_id' )
            );

            $subscription = auth()->user()
            ->subscriptions()
            ->find($active_subscription_id);

            // add the site with the subscription
            if ( !is_null( $subscription ) ) {
                $subscriptionService = new SubscriptionService( $subscription );
                $subscriptionService->addSite([
                    'subscription_id'   => $subscription->id,
                    'url'               => $plugin_redirect_url
                ]);
            }

            return redirect()->away($plugin_redirect_url);
        }
    }
}
