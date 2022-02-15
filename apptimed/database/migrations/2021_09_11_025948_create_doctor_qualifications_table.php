<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_qualifications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('doctor')->unsigned()->index();
            $table->foreign('doctor')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('qualification');
            $table->year('year')->nullable();

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
        Schema::dropIfExists('doctor_qualifications');
    }
}
