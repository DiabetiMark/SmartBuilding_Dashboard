<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username', 45)->nullable(false)->unique();
            $table->string('password', 60)->nullable(false);
            $table->string('email', 255)->nullable(false)->unique();
            $table->string('name', 45)->nullable(false);
            $table->string('phone', 30)->nullable(false);
            $table->integer('role_id')->nullable(false)->default(1);
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
