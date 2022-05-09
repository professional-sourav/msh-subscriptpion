<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); 
            $table->foreign('product_id')->references('id')->on('products');            
            $table->string("stripe_id")->nullable();
            $table->enum('pricing_model',['standard','package', 'graduated', 'volume'])->default('standard');
            $table->enum('price_type',['recurring','oneTime'])->default('recurring');
            $table->enum('billing_period',['day','week', 'month', 'quarter', 'semiannual', 'year', 'custom']);
            $table->string('custom_billing_period')->nullable();
            $table->boolean('usage_metered')->default(false);
            $table->boolean('free_trial')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_prices');
    }
}
