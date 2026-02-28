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
        Schema::create('ip_bans', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique()->index();  // IPv6 support
            $table->integer('failed_attempts')->unsigned()->default(0);
            $table->dateTime('last_attempt_at')->nullable();
            $table->dateTime('banned_until')->nullable()->index();
            $table->text('reason')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_bans');
    }
};
