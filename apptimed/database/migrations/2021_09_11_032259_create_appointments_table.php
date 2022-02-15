<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('patient')->unsigned()->index()->nullable();
            $table->foreign('patient')->references('id')->on('patients')->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('patient_name')->nullable();
            $table->date('patient_dob')->nullable();
            $table->string('patient_gender')->nullable();

            $table->bigInteger('doctor')->unsigned()->index();
            $table->foreign('doctor')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('doctor_timeslot')->unsigned()->index();
            $table->foreign('doctor_timeslot')->references('id')->on('doctor_timeslots')->onUpdate('cascade')->onDelete('cascade');
            
            $table->integer('allocated_time');
            $table->date('date');
            $table->time('time_from');
            $table->string('appointment_number');
            $table->text('notes')->nullable();
            $table->text('prescriptions')->nullable();
            $table->string('prescriptions_file')->nullable();
            $table->double('doctor_fees');
            $table->double('app_fees');

            $table->bigInteger('payment')->unsigned()->index()->nullable();
            $table->foreign('payment')->references('id')->on('payments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->double('payment_wallet')->nullable();
            $table->double('payment_card')->nullable();
            
            $table->string('status')->nullable();//pending, confirmed, cancelled by patient, cancelled by doctor
            $table->text('hangout_link')->nullable();

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
        Schema::dropIfExists('appointments');
    }
}
