<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductPriceRequest;
use App\Services\StripeService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class ProductPriceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductPriceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ProductPrice::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-price');
        CRUD::setEntityNameStrings('product price', 'product prices');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('billing_period');
        // CRUD::column('created_at');
        CRUD::column('custom_billing_period');
        CRUD::column('free_trial');
        // CRUD::column('id');
        CRUD::column('is_default');
        CRUD::column('price_type');
        CRUD::column('pricing_model');
        CRUD::column('product_id');
        // CRUD::column('stripe_id');
        // CRUD::column('updated_at');
        CRUD::column('usage_metered');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductPriceRequest::class);

        $this->crud->addField([
            'label'     => "Products",
            'type'      => 'select',
            'name'      => 'product_id', // the db column for the foreign key

            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            // 'entity'    => 'products',

            // optional - manually specify the related model and attribute
            'model'     => "App\Models\Product", // related model
            'attribute' => 'title', // foreign key attribute that is shown to user

            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                                return $query->orderBy('title', 'ASC')->get();
                            }), //  you can use 
            // 'tab'             => 'Tab name here',
        ]);

        // CRUD::field('billing_period');
        $this->crud->addField(
            [   // select_from_array
                'name'        => 'billing_period',
                'label'       => "Billing Period",
                'type'        => 'select_from_array',
                'options'     => [
                    'day', 
                    'week', 
                    'month', 
                    'quarter', 
                    'semiannual',
                    'year'
                ],
                'allows_null' => false,
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
        );
        // CRUD::field('created_at');
        CRUD::field('custom_billing_period');
        CRUD::field('free_trial');
        // CRUD::field('id');
        CRUD::field('is_default');
        // CRUD::field('price_type');
        $this->crud->addField(
            [   // select_from_array
                'name'        => 'price_type',
                'label'       => "Price Type",
                'type'        => 'select_from_array',
                'options'     => ['Select', 'recurring', 'oneTime'],
                'allows_null' => false,
                'default'     => 1,
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
        );
        // CRUD::field('pricing_model');
        $this->crud->addField(
            [   // select_from_array
                'name'        => 'pricing_model',
                'label'       => "Pricing Model",
                'type'        => 'select_from_array',
                'options'     => [
                    'Select',
                    'standard', 
                    'package', 
                    'graduated', 
                    'volume'
                ],
                'allows_null' => false,
                'default'     => 'standard',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
        );
        // CRUD::field('product_id');
        // CRUD::field('stripe_id');
        // CRUD::field('updated_at');
        CRUD::field('usage_metered');

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

    public function store(Request $request) {
        
        // do something before validation, before save, before everything
        
        $response = $this->traitStore();

        // do something after save
        // echo json_encode($response); die;

        /**
         * Add the product to the Stripe
         */
        $stripeService = new StripeService();
        $stripeService->attachProductPrice([
            "name"          => $request->title,
            "active"        => boolval( $request->status ),
            "images"        => (!empty($request->image_url)) ? [ $request->image_url ] : [],
            "description"   => $request->description,
        ]);


        return $response;
    }
}
