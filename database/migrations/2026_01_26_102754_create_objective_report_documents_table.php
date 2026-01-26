<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objective_report_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('objective_report_id')->constrained()->onDelete('cascade');
            
            $table->string('file_path');
            $table->string('file_type')->nullable(); // image, pdf
            $table->string('file_name')->nullable();
            
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objective_report_documents');
    }
};
