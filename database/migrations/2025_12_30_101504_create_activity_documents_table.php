<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            
            $table->string('file_path');
            $table->string('file_type')->nullable(); // image, pdf, document
            $table->text('description')->nullable();
            
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('uploaded_at');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_documents');
    }
};
