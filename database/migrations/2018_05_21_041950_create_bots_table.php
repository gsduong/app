<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->text('settings')->nullable();
            $table->string('access_token');
            $table->boolean('active')->default(1);
            $table->string('welcome_message')->nullable();
            $table->string('default_response')->nullable();
            $table->text('default_response_in_maintenance')->nullable();
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
        Schema::dropIfExists('bots');
    }
}
