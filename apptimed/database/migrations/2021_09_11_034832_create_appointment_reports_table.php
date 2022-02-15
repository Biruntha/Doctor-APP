<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_reports', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('patient')->unsigned()->index();
            $table->foreign('patient')->references('id')->on('patients')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('appointment')->unsigned()->index();
            $table->foreign('appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->text('description')->nullable();
            $table->string('file');

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
        Schema::dropIfExists('appointment_reports');
    }
}
