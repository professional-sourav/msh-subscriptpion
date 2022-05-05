<?php

namespace App\Services;

use App\Models\Plan;

class PaymentService
{
    public function validatePlanByIdentifier($identifier) {

        $plan = Plan::where( 'identifier', $identifier )->first();

        return !is_null( $plan );
    }
}