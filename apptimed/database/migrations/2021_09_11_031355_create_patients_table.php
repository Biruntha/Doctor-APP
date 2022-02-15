<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('user')->unsigned()->index()->unique();
            $table->foreign('user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->double('latititude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_holder_name')->nullable();

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
        Schema::dropIfExists('patients');
    }
}
