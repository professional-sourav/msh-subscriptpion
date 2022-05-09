<?php

namespace App\Services;

use Stripe\StripeClient;

class StripeService
{
    protected $stripe;

    protected $publishable_key;

    protected $secret_key;


    public function __construct()
    {
        $this->publishable_key  = config( "stripe.publishable_key" );
        $this->secret_key       = config( "stripe.secret_key" );

        $this->stripe =   new StripeClient( $this->secret_key );
    }

    public function getPlans()
    {
        return json_encode($this->stripe->plans->all());
    }

    public function getSubscription($subscription_id)
    {
        return $this->stripe->subscriptions->retrieve( $subscription_id );
    }

    public function createProduct(array $data) {

        if ( !empty( $data['name'] ) ) {

            $this->stripe->products->create( $data );
        }
    }
}
