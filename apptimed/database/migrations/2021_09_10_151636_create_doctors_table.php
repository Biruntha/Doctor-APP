<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user')->unsigned()->index()->unique();
            $table->foreign('user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('title');
            $table->string('registration_id')->unique();
            $table->integer('timeslot_duration');
            $table->double('fees');
            $table->double('app_fees');
            $table->string('bank_branch');
            $table->string('bank_account_no');
            $table->string('bank_holder_name');

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
        Schema::dropIfExists('doctors');
    }
}
