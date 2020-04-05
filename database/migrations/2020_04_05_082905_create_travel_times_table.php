<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_times', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('origin');
            $table->text('duration');
            $table->text('duration_in_traffic');
            $table->unsignedDecimal('difference', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_times');
    }
}
