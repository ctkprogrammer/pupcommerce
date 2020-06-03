<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('price')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('final_price')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamp('actual_delivery_time')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_state')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('delivery_address')->nullable();            
            $table->string('delivery_zipcode')->nullable();
            $table->string('delivery_phone')->nullable();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('buyer_id')->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers');
            $table->bigInteger('pup_id')->unsigned();
            $table->foreign('pup_id')->references('id')->on('pups');
            $table->bigInteger('review_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('reviews');

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
        Schema::dropIfExists('shipments');
    }
}
