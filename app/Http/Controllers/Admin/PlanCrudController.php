<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Services\StripeService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

/**
 * Class PlanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Plan::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/plan');
        CRUD::setEntityNameStrings('plan', 'plans');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        // CRUD::column('stripe_id');
        CRUD::column('title');
        CRUD::column('identifier');
        CRUD::column('word_limit');
        CRUD::column('created_at');
        // CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

        $this->crud->removeButton("create");

        // add the sync button
        $this->crud->addButtonFromView('top', 'syncWithStripe', 'price-sync-with-stripe', 'end');

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PlanRequest::class);

        // CRUD::field('created_at');
        // CRUD::field('id');
        CRUD::field('title');
        CRUD::field('identifier');
        CRUD::field('word_limit');
        // CRUD::field('stripe_id');
        // CRUD::field('updated_at');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function update()
    {
        // do something before validation, before save, before everything

        $response = $this->traitUpdate();

        // $this->crud->entry => Plan

        // update the price on Stripe
        $stripeService = new StripeService();
        $stripeService->updateProductPrices( $this->crud->entry->stripe_id, [
            'nickname' => $this->crud->entry->title,
            'metadata' => [
                'word_limit' => $this->crud->entry->word_limit ?? null
            ]
        ] );

        return $response;
    }

    public function syncWithStripe(Request $request)
    {
        $stripeService = new StripeService();
        
        // retrive the prices from Stripe
        $prices = $stripeService->getProductPrices();

        if ( !is_null( $prices['data'] ) ) {

            // store the prices in the DB
            foreach ( $prices['data'] as $priceData ) {

                // if the price_id already in the DB, ignore it
                $plan = Plan::where( "stripe_id", $priceData->id )->first();

                if ( is_null( $plan ) ) {

                    $title = $priceData->unit_amount_decimal / 100 . "$ / " . $priceData->recurring->interval;

                    $plan = new Plan();
                    $plan->title        = $priceData->nickname ?? $title;
                    $plan->identifier   = Str::slug( $priceData->nickname ?? $title );
                    $plan->stripe_id    = $priceData->id;
                    
                    // Store metadata in the key exists as a column on the table
                    if ( !empty( $priceData->metadata ) ) {

                        $metadata_arr = json_decode( json_encode($priceData->metadata), true );

                        foreach ( $metadata_arr as $column=>$metadata ) {

                            if (Schema::hasColumn('plans', $column)) {
                                
                                $plan->{$column} = $metadata;
                            }
                        }
                    }

                    $plan->save();
                }
            }

            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }
}
