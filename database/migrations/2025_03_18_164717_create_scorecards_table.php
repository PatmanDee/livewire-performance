<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scorecards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('scorecard_model_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', [
                'draft',
                'active',
                'completed',
                'archived'
            ])->default('draft');
            $table->text('employee_comments')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->text('reviewer_comments')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();


            /**
             * Relationships
             */
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('scorecard_model_id')->references('id')->on('scorecard_models')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scorecards');
    }
};