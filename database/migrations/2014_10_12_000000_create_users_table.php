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
            $table->smallInteger('user_type_id');
            $table->integer('branch_id');
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('gender', 10)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('civil_status', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->date('birthdate')->nullable();
            $table->date('join_date')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('password');
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
