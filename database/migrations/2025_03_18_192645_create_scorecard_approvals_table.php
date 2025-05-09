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
        Schema::create('scorecard_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scorecard_id');
            $table->text('owner_comments')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->date('submitted_at')->nullable();
            $table->date('reviewed_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            /**
             * Relationships
             */
            $table->foreign('scorecard_id')->references('id')->on('scorecards')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorecard_approvals');
    }
};
