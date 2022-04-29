<?php

namespace App\Services;

use Cartalyst\Stripe\Laravel\Facades\Stripe;

class StripeService
{
    protected $stripe;

    protected $publishable_key;

    protected $secret_key;


    public function __construct()
    {
        $this->publishable_key  = config( "stripe.publishable_key" );
        $this->secret_key       = config( "stripe.secret_key" );

        $this->stripe = new Stripe( $this->publishable_key, $this->secret_key );
    }
}
