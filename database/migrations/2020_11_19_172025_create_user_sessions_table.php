<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersessions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique();
            $table->string('login_time');
            $table->string('ip_address');
            $table->string('browser');
            $table->string('geolocation');
            $table->string('device');
            $table->string('login_type');
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
        Schema::dropIfExists('usersessions');
    }
}
