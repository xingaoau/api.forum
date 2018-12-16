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
            $table->string('name');
            $table->string('username')->uniqnue();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('realname')->nullable();

            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('bio')->nullable();
            $table->json('extends')->nullable();
            $table->json('settings')->nullable();

            $table->integer('level')->default(0);
            $table->boolean('is_admin')->default(false);

            $table->json('cache')->nullable();

            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamp('activated_at')->nullable();
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
