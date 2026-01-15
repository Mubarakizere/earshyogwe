<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_custom_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('custom_field_definition_id')->constrained()->onDelete('cascade');
            $table->text('field_value')->nullable(); // Stores the actual value
            $table->timestamps();
            
            // Ensure one value per field per activity
            $table->unique(['activity_id', 'custom_field_definition_id'], 'activity_custom_field_unique');
            $table->index('activity_id');
            $table->index('custom_field_definition_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_custom_values');
    }
};
