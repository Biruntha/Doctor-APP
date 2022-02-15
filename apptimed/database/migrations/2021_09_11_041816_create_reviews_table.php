<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('appointment')->unsigned()->index()->unique();
            $table->foreign('appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('patient')->unsigned()->index();
            $table->foreign('patient')->references('id')->on('patients')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('doctor')->unsigned()->index();
            $table->foreign('doctor')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            
            $table->double('rating');
            $table->text('review')->nullable();

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
        Schema::dropIfExists('reviews');
    }
}
