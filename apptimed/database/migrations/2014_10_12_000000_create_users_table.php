<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('type'); //Doctor or Patient or Internal User
            $table->string('code')->unique()->nullable();

            $table->string('fname');
            $table->string('lname');
            $table->string('oname')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('contact')->unique();
            $table->timestamp('contact_verified_at')->nullable();

            $table->string('contact_secondary')->unique()->nullable();

            $table->bigInteger('role')->unsigned()->index()->nullable(); //HR manager in Company, Accountact in Internal
            $table->foreign('role')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('password');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
                        
            $table->text('remarks')->nullable();
            $table->boolean('black_listed')->default(0);
            $table->string('registered_mode')->default("ONLINE_SELF");
            $table->string('found_mode')->default("None"); //Facebook/Instagram Ads,Google Ads,Heard from colleague,Other

            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
