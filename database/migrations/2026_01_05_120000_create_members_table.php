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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('sex', ['Male', 'Female']);
            $table->date('dob')->nullable(); // For Age
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->enum('parental_status', ['Orphan', 'Living with both parents', 'Living with one parent', 'Under guardian/Caregiver']);
            $table->enum('baptism_status', ['Baptized', 'Confirmed', 'None']);
            $table->string('church_group')->nullable(); // Mothers' Union, etc.
            $table->enum('education_level', ['Primary', 'Secondary', 'University', 'None', 'Other'])->nullable();
            $table->json('extra_attributes')->nullable(); // For dynamic columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
