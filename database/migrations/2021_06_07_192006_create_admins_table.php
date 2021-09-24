<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('role_id')->default(0);
            $table->foreign('role_id')->references('id')->on('admin_roles')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_id')->unique();
            $table->string('mobile_number', 20)->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(0);
            $table->text('email_verify_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verify_token_expire_at')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
