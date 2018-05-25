<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('customer_id')->nullable(); // the order can be placed by restaurant's staff
            $table->unsignedInteger('restaurant_id');
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('customer_note')->nullable();
            $table->decimal('total', 8, 2);
            $table->unsignedInteger('last_editor_id')->nullable();
            $table->unsignedInteger('creator_id')->nullable(); // create by bot, by guest user or by restaurant's staff
            $table->boolean('created_by_bot')->default(1);
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, delivering, delivered
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('contact_infos')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
}
