<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderStatusPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_request_order_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_request_id');
            $table->bigInteger('order_status_id');
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
        Schema::table('order_request_order_status', function (Blueprint $table) {
            //
        });
    }
}
