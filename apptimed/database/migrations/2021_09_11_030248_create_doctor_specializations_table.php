<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorSpecializationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_specializations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('doctor')->unsigned()->index();
            $table->foreign('doctor')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('specialization')->unsigned()->index();
            $table->foreign('specialization')->references('id')->on('specializations')->onUpdate('cascade')->onDelete('cascade');
            
            $table->year('experience_year')->nullable();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('doctor_specializations');
    }
}
