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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            
            // Diocese/Region information
            $table->string('diocese')->nullable();
            $table->string('region')->nullable();
            
            // Archid assignment (regional supervisor)
            $table->foreignId('archid_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Pastor assignment
            $table->foreignId('pastor_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
