<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pup_name')->nullable();
            $table->integer('price')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('breed_id')->unsigned();
            $table->foreign('breed_id')->references('id')->on('breeds');
            $table->bigInteger('shipment_id')->unsigned();
          
            $table->string('photo_url')->nullable();
            $table->string('video_url')->nullable();
            $table->date('birth')->nullable();
            $table->string('gender')->nullable();
            $table->integer('current_weight')->nullable();
            $table->integer('adult_weight')->nullable();
            $table->string('neopar_vaccine')->nullable();
            $table->string('drumune_max')->nullable();
            $table->string('pyrantel_deworm')->nullable();
            $table->string('vet_inspection')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('pups');
    }
}
