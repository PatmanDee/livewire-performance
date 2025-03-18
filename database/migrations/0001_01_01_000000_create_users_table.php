<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('employee_level')->nullable();
            $table->string('position')->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated', 'on_leave'])->default('active');
            $table->boolean('is_super_admin')->default(false);
            $table->string('workforce_id')->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('marital_status')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('primary_contact_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('business_units', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users')->nullOnDelete();
        });

    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['primary_contact_id']);
        });

        Schema::table('business_units', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['head_id']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
        });

        Schema::dropIfExists('users');
    }
};