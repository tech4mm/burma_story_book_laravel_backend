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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_name')->nullable();      // e.g., iPhone 13, Pixel 6
            $table->string('device_os')->nullable();        // e.g., iOS, Android
            $table->string('os_version')->nullable();       // e.g., 17.2.1
            $table->string('app_version')->nullable();      // e.g., 1.0.5
            $table->ipAddress('ip_address')->nullable();    // Store IP
            $table->timestamp('last_login_at')->nullable(); // Useful for last seen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
