<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('status')->default('open'); //open, closed, completed, canceled
            $table->unsignedInteger('user_id');

            $table->dateTime('arrival_date');
            $table->string('arrival_type');
            $table->tinyInteger('biddable');
            $table->string('departure_address');
            $table->dateTime('departure_date');
            $table->string('departure_lat');
            $table->string('departure_lng');
            $table->string('departure_type');
            $table->string('destination_address');
            $table->string('destination_lat');
            $table->string('destination_lng');
            $table->tinyInteger('first_bid_buy');
            $table->integer('budget')->nullable();

        });

        Schema::create('transport_pet', function (Blueprint $table) {
            $table->integer('transport_id')->unsigned();
            $table->integer('pet_id')->unsigned();

            $table->foreign('transport_id')
                ->references('id')
                ->on('transports')
                ->onDelete('cascade');

            $table->foreign('pet_id')
                ->references('id')
                ->on('pets')
                ->onDelete('cascade');

            $table->primary(['transport_id', 'pet_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transports');
    }
}
