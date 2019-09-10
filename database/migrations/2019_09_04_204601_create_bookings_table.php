<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('booking_no');
            $table->integer('room_type_id');
            $table->string('member_id');
            $table->string('name');
            $table->string('cell_no');
            $table->string('email');
            $table->text('address');
            $table->string('purpose');
            $table->integer('no_guest');
            $table->date('booking_from');
            $table->date('booking_to');
            $table->integer('room_rent');
            $table->integer('room_qty');
            $table->integer('total_amount');
            $table->string('payment_mood');
            $table->boolean('status')->default(0);
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
