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
            $table->string('default_response_in_maintenance')->default("Hiện tại chúng tôi đang trong quá trình cập nhật thông tin. Mong quý khách vui lòng quay lại sau. Xin cảm ơn!");
            $table->boolean('menu')->default(1);
            $table->boolean('order')->default(1);
            $table->boolean('address')->default(1);
            $table->boolean('opening_hour')->default(1);
            $table->boolean('phone_number')->default(1);
            $table->boolean('booking')->default(1);
            $table->boolean('chat_with_staff')->default(1);
            $table->string('msg_menu')->default("Hiện tại menu của nhà hàng vẫn đang trong quá trình hoàn thiện. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
            $table->string('msg_address')->default("Hiện tại nhà hàng chưa cập nhật địa chỉ. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
            $table->string('msg_order')->default("Hiện tại nhà hàng không tiếp nhận đơn đặt hàng online. Mong quý khách vui lòng liên hệ lại sau! Xin cảm ơn!");
            $table->string('msg_opening_hour')->default("Hiện tại nhà hàng chưa có giờ mở cửa cụ thể. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
            $table->string('msg_phone_number')->default("Hiện tại nhà hàng chưa cập nhật số điện thoại. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
            $table->string('msg_booking')->default("Hiện tại nhà hàng không nhận đặt bàn online. Mong quý khách vui lòng chờ đợi trong thời gian ngắn! Xin cảm ơn!");
            $table->string('msg_chat_with_staff')->default("Hiện tại không có nhân viên nào trực tuyến. Mong quý khách vui lòng chờ đợi trong thời gian ngắn, nhà hàng sẽ liên lạc lại với quý khách! Xin cảm ơn!");
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
