<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubscriptionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscriptionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Subscription::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscription');
        CRUD::setEntityNameStrings('subscription', 'subscriptions');

        // we don't want to delete the subscription
        $this->crud->denyAccess('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::column('id');
        CRUD::column('user');
        CRUD::column('plan');
        // CRUD::column('name');
        // CRUD::column('stripe_id');
        $this->crud->addColumn(['name' => 'stripe_status', 'type' => 'text', 'label' => 'Activation Status']);
        // CRUD::column('stripe_price');
        CRUD::column('quantity');
        CRUD::column('trial_ends_at');
        CRUD::column('ends_at');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(SubscriptionRequest::class);

        CRUD::field('id');
        CRUD::field('user_id');
        CRUD::field('name');
        CRUD::field('stripe_id');
        // CRUD::field('stripe_status');

        $this->crud->addField([
            'name'            => 'stripe_status',
            'label'           => "Select Activation Status",
            'type'            => 'select_from_array',
            'hint'            => "Stripe has mentioned in the API, https://stripe.com/docs/api/subscriptions/list#list_subscriptions-status",
            'options'         => [
                'all'                   => 'All',
                'active'                => 'Active', 
                'past_due'              => 'Past Due', 
                'unpaid'                => 'Unpaid',
                'canceled'              => 'Canceled',
                'incomplete'            => 'Incomplete',
                'incomplete_expired'    => 'Incomplete Expired',
                'trialing'              => 'Trialing',
                'ended'                 => 'Ended'
            ],
            // 'allows_null'     => false,
            // 'allows_multiple' => true,
            // 'tab'             => 'Tab name here',
        ]);

        CRUD::field('stripe_price');
        CRUD::field('quantity');
        CRUD::field('trial_ends_at');
        CRUD::field('ends_at');
        CRUD::field('created_at');
        CRUD::field('updated_at');

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
