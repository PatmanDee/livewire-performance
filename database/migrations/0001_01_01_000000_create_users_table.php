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
            $table->unsignedBigInteger('company_id')->nullable(); // Reference to the company
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('business_unit_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('scorecard_model_id')->nullable();
            $table->unsignedBigInteger('account_type_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('position')->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated', 'on_leave'])->default('active');
            $table->boolean('is_super_admin')->default(false);
            $table->enum('special_rights', ['none', 'admin_rights', 'full_rights'])->default('none');
            $table->string('workforce_id')->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            /**
             * Relationships
             */
            $table->foreign('supervisor_id')->references('id')->on('users')->cascadeOnDelete(); // Self-referencing for supervisor
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->foreign('business_unit_id')->references('id')->on('business_units')->cascadeOnDelete();
            $table->foreign('scorecard_model_id')->references('id')->on('scorecard_models')->cascadeOnDelete();
            $table->foreign('account_type_id')->references('id')->on('account_types')->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};