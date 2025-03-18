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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('scorecard_model_id');
            $table->integer('level');
            $table->string('name');
            $table->string('group_name');
            $table->timestamps();

            /**
             * Relationships
             */
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('scorecard_model_id')->references('id')->on('scorecard_models')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
