<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id');

            $table->bigInteger('perform_by')->unsigned()->index()->nullable();
            $table->foreign('perform_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('to_user')->unsigned()->index();
            $table->foreign('to_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->text('notification');
            $table->boolean('is_read');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
