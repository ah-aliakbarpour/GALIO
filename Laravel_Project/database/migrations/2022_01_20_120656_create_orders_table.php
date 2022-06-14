<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['Pending', 'Processing', 'Completed'])->default('Pending');
            $table->string('country');
            $table->string('city');
            $table->mediumText('address');
            $table->string('phone');
            $table->enum('payment_method', ['Credit Card', 'Cash On Delivery', 'Paypal']);
            $table->unsignedDecimal('shipping', 20, 2);
            $table->unsignedDecimal('coupon', 20, 2)->nullable();
            $table->unsignedDecimal('total_products_price', 20, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
