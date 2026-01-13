<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->string('document_name');
            $table->string('document_type')->nullable();
            $table->string('file_path');
            $table->timestamps();
            
            // Index
            $table->index('worker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_documents');
    }
};
