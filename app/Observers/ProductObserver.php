<?php
 
namespace App\Observers;
 
use App\Models\Product;
use App\Services\StripeService;

class ProductObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(Product $product)
    {
        /**
         * Add the product to the Stripe
         */
        $stripeService = new StripeService();
        $stripeProduct = $stripeService->createProduct([
            "name"          => $product->title,
            "active"        => boolval( $product->status ),
            "images"        => (!empty($product->image_url)) ? [ $product->image_url ] : [],
            "description"   => $product->description,
        ]);

        // update the Stripe id
        $product->update([ "stripe_id" => $stripeProduct->id ]);
    }
 
    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }
 
    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}