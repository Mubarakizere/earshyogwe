<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            
            $table->string('indicator_name');
            $table->decimal('target_value', 10, 2);
            $table->decimal('current_value', 10, 2)->default(0);
            $table->string('unit')->nullable(); // e.g., 'people', 'hours', 'RWF'
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_indicators');
    }
};
