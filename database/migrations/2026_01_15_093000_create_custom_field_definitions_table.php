<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // e.g., "Number of Bibles Distributed"
            $table->string('field_key'); // e.g., "bibles_distributed" (unique within department)
            $table->enum('field_type', ['text', 'textarea', 'number', 'date', 'select', 'checkbox'])->default('text');
            $table->json('field_options')->nullable(); // For select dropdowns: ["Option 1", "Option 2"]
            $table->boolean('is_required')->default(false);
            $table->text('help_text')->nullable(); // Instructions for users
            $table->integer('display_order')->default(0); // For ordering fields
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure unique field keys within each department
            $table->unique(['department_id', 'field_key']);
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_definitions');
    }
};
