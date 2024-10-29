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
            $table->string('type');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('data_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->unique()->nullable();
            $table->integer('x')->nullable();
            $table->integer('y')->nullable();
            $table->string('mac_address');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mac_address')->references('mac_address')->on('devices');
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->foreignId('data_room_id')->constrained('data_rooms');
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
