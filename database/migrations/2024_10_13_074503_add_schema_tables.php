<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mac_address')->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address_device');
            $table->json('status_beacon');
            $table->json('data');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mac_address_device')->references('mac_address')->on('devices');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('data_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('device_connected');
            $table->integer('human');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('log_id')->constrained('logs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rooms');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('rooms');
    }
};
