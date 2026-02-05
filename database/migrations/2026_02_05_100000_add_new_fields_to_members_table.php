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
        Schema::table('members', function (Blueprint $table) {
            $table->string('chapel')->nullable()->after('church_id');
            $table->string('disability')->nullable()->after('education_level');
            $table->string('parent_names')->nullable()->after('parental_status');
            $table->foreignId('recorded_by')->nullable()->after('deceased_cause')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
            $table->dropColumn(['chapel', 'disability', 'parent_names', 'recorded_by']);
        });
    }
};
