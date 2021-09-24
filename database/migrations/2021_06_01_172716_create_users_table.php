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
            $table->bigIncrements('id');
            $table->text('hash_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_id')->unique();
            $table->string('username')->unique();
            $table->string('mobile_number', 20)->nullable();
            $table->string('password');
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->tinyInteger('is_owner')->default(0)->comment('0=No, 1=Yes');
            $table->tinyInteger('status')->default(0);
            $table->text('email_verify_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verify_token_expire_at')->nullable();
            $table->timestamp('signup_completed_at')->nullable();
            $table->tinyInteger('agree_signup_terms')->default(0)->comment('0=No, 1=Yes');
            $table->string('profile_image')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
