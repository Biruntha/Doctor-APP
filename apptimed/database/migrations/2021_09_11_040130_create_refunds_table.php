<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('appointment')->unsigned()->index();
            $table->foreign('appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('cancellation')->unsigned()->index();
            $table->foreign('cancellation')->references('id')->on('cancellations')->onUpdate('cascade')->onDelete('cascade');
            
            $table->double('amount');
            $table->string('mode'); //deposit
            $table->string('status');//pending, done

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
        Schema::dropIfExists('refunds');
    }
}
