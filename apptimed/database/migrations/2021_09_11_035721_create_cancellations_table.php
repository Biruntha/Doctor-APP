<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('appointment')->unsigned()->index();
            $table->foreign('appointment')->references('id')->on('appointments')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('cancelled_by')->unsigned()->index();
            $table->foreign('cancelled_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('reason')->unsigned()->index()->nullable();
            $table->foreign('reason')->references('id')->on('cancellation_reasons')->onUpdate('cascade')->onDelete('cascade');
            
            $table->text('comments')->nullable();
            $table->string('bank_branch');
            $table->string('bank_account_no');
            $table->string('bank_holder_name');

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
        Schema::dropIfExists('cancellations');
    }
}
