<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Services\StripeService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('created_at');
        CRUD::column('deleted_at');
        CRUD::column('description');
        CRUD::column('id');
        CRUD::column('image_url');
        CRUD::column('product_type');
        CRUD::column('status');
        CRUD::column('stripe_id');
        CRUD::column('title');
        CRUD::column('updated_at');
        CRUD::column('user_id');

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
        CRUD::setValidation(ProductRequest::class);

        // CRUD::field('created_at');
        // CRUD::field('deleted_at');
        CRUD::field('title');
        CRUD::field('description');
        // CRUD::field('id');
        CRUD::field('product_type');
        CRUD::field('image_url');
        CRUD::addField([
            'name' => 'status',
            'type' => 'checkbox',
            'value' => true
        ]);
        CRUD::addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' =>  backpack_user()->id
        ]);
        // CRUD::field('stripe_id');
        // CRUD::field('updated_at');
        // CRUD::field('user_id');

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
        $stripeService->createProduct([
            "name"          => $request->title,
            "active"        => boolval( $request->status ),
            "images"        => (!empty($request->image_url)) ? [ $request->image_url ] : [],
            "description"   => $request->description,
        ]);


        return $response;
    }
}
