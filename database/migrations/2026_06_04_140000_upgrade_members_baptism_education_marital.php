<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new baptism boolean columns
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('is_baptized')->default(false)->after('church_group');
            $table->boolean('is_confirmed')->default(false)->after('is_baptized');
        });

        // 2. Migrate existing baptism_status data
        DB::table('members')->where('baptism_status', 'Baptized')->update([
            'is_baptized' => true,
            'is_confirmed' => false,
        ]);
        DB::table('members')->where('baptism_status', 'Confirmed')->update([
            'is_baptized' => true,
            'is_confirmed' => true,
        ]);
        // 'None' stays as default (false, false)

        // 3. Drop old baptism_status column
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('baptism_status');
        });

        // 4. Change marital_status from enum to string (allows adding new options without migration)
        Schema::table('members', function (Blueprint $table) {
            $table->string('marital_status')->change();
        });

        // 5. Change education_level from enum to string (allows adding new options without migration)
        Schema::table('members', function (Blueprint $table) {
            $table->string('education_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add baptism_status
        Schema::table('members', function (Blueprint $table) {
            $table->string('baptism_status')->default('None')->after('church_group');
        });

        // Migrate data back
        DB::table('members')->where('is_confirmed', true)->update(['baptism_status' => 'Confirmed']);
        DB::table('members')->where('is_baptized', true)->where('is_confirmed', false)->update(['baptism_status' => 'Baptized']);

        // Drop new columns
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['is_baptized', 'is_confirmed']);
        });
    }
};
