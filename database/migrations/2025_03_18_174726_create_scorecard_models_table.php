<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scorecard_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('uses_perspectives')->default(true);
            $table->boolean('uses_dual_targets')->default(false); // Base and stretch targets
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            /**
             * Relationships
             */
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scorecard_models');
    }
};