<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("amount");
            $table->string("names");
            $table->string("phone_number");
            $table->boolean("payed")->default(false);
            $table->boolean("scanned")->default(false);
            $table->foreignId("destination_id")->references("id")->on("destinations");
            $table->string("payment_mode")->nullable();
            $table->string("transaction_id")->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
