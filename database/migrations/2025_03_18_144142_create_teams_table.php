<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('business_unit_id');
            $table->unsignedBigInteger('leader_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            /**
             * Relationships
             */
            $table->foreign('business_unit_id')->references('id')->on('business_units')->cascadeOnDelete();
            $table->foreign('leader_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};