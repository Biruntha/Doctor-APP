<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user')->unsigned()->index();
            $table->foreign('user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
          
            $table->bigInteger('appointment')->unsigned()->index();
            $table->foreign('appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->double('amount_in')->nullable();
            $table->double('amount_out')->nullable();

            $table->text('description')->nullable();

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
        Schema::dropIfExists('wallets');
    }
}
