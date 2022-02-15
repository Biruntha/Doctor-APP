<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('from_appointment')->unsigned()->index();
            $table->foreign('from_appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('to_appointment')->unsigned()->index();
            $table->foreign('to_appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('transfer_reason')->unsigned()->index()->nullable();
            $table->foreign('transfer_reason')->references('id')->on('transfer_reasons')->onUpdate('cascade')->onDelete('cascade');
            
            $table->text('comments')->nullable();
            $table->double('transfer_amount');

            $table->bigInteger('payment')->unsigned()->index();
            $table->foreign('payment')->references('id')->on('payments')->onUpdate('cascade')->onDelete('cascade');
            
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
        Schema::dropIfExists('transfers');
    }
}
