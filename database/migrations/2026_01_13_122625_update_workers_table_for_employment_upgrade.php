<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            // Add new fields for comprehensive employment information
            $table->enum('gender', ['male', 'female'])->after('last_name');
            $table->string('national_id')->nullable()->after('gender');
            $table->string('education_qualification')->nullable()->after('national_id');
            $table->string('district')->nullable()->after('phone');
            $table->string('sector')->nullable()->after('district');
            
            // Add institution reference
            $table->foreignId('institution_id')->nullable()->after('church_id')->constrained()->onDelete('set null');
            
            // Rename position to job_title for clarity
            $table->renameColumn('position', 'job_title');
            
            // Remove old fields that are no longer needed
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
            $table->dropColumn('retirement_age');
        });
    }

    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn([
                'gender',
                'national_id',
                'education_qualification',
                'district',
                'sector'
            ]);
            
            $table->dropForeign(['institution_id']);
            $table->dropColumn('institution_id');
            
            $table->renameColumn('job_title', 'position');
            
            // Re-add old fields
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('retirement_age')->default(60);
        });
    }
};
