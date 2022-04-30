<?php

namespace App\Services;

use App\Models\Site;
use Laravel\Cashier\Subscription;

class SubscriptionService
{
    private $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function addSite(array $data)
    {
        $domain = parse_url( $data['url'] );

        $domain_url = "";

        if ( !empty($domain["scheme"]) )
            $domain_url .= $domain["scheme"] . "://";

            
        if ( !empty($domain["host"]) )
            $domain_url .= $domain["host"];

        if ( !empty($domain["port"]) )
            $domain_url .= ":" . $domain["port"];

        return Site::firstOrCreate([
            'subscription_id'       => $data['subscription_id'],
            'subscription_item_id'  => 1,
            'url'                   => $domain_url . DIRECTORY_SEPARATOR
        ]);
    }
}