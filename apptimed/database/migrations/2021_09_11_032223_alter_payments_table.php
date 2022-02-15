<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('payments', 'tax')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->double('tax')->after('amount')->nullable();
            });
        }
        if (!Schema::hasColumn('payments', 'paid_mode')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('paid_mode')->after('tax'); //deposit, card
            });
        }
        if (!Schema::hasColumn('payments', 'payment_attachment')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('payment_attachment')->after('paid_mode')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
