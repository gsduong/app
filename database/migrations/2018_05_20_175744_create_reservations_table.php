<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->time('time');
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_phone');
            $table->string('customer_name');
            $table->integer('adult')->default(0);
            $table->integer('children')->default(0);
            $table->string('customer_requirement')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->unsignedInteger('last_editor_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->foreign('last_editor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('contact_infos')->onDelete('set null');
            $table->string('status')->default('pending'); // pending - cancelled - confirmed
            $table->boolean('created_by_bot')->default(0);
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
        Schema::dropIfExists('reservations');
    }
}
