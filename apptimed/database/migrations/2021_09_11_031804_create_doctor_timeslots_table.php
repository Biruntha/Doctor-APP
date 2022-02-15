<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorTimeslotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_timeslots', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();

            $table->bigInteger('doctor')->unsigned()->index();
            $table->foreign('doctor')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            
            $table->date('date');
            $table->time('time_from');
            $table->time('time_to');
            $table->integer('max_appointments')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_timeslots');
    }
}
