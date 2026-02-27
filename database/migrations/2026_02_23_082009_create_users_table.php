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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 60)->unique();
            $table->string('user_password', 100);
            $table->string('user_full_name', 100)->nullable();
            $table->string('user_email', 100)->nullable()->unique();
            $table->string('user_url', 100)->nullable();
            $table->bigInteger('user_group_id')->default(0)->index();
            $table->enum('user_type', ['super_user', 'administrator', 'employee', 'student'])->default('administrator');
            $table->bigInteger('user_profile_id')->nullable()->comment('student_id OR employee_id')->index();
            $table->text('user_biography')->nullable();
            $table->string('user_forgot_password_key', 100)->nullable();
            $table->date('user_forgot_password_request_date')->nullable();
            $table->enum('has_login', ['true', 'false'])->default('false');
            $table->datetime('last_logged_in')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
