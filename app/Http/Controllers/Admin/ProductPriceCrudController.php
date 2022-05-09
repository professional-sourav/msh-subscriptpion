<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductPriceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductPriceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductPriceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::column('created_at');
        CRUD::column('custom_billing_period');
        CRUD::column('free_trial');
        CRUD::column('id');
        CRUD::column('is_default');
        CRUD::column('price_type');
        CRUD::column('pricing_model');
        CRUD::column('product_id');
        CRUD::column('stripe_id');
        CRUD::column('updated_at');
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

        CRUD::field('billing_period');
        CRUD::field('created_at');
        CRUD::field('custom_billing_period');
        CRUD::field('free_trial');
        CRUD::field('id');
        CRUD::field('is_default');
        CRUD::field('price_type');
        CRUD::field('pricing_model');
        CRUD::field('product_id');
        CRUD::field('stripe_id');
        CRUD::field('updated_at');
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
}
