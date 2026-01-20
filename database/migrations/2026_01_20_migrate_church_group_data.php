<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\ChurchGroup;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all unique church group names from existing data
        $uniqueGroups = DB::table('members')
            ->whereNotNull('church_group')
            ->where('church_group', '!=', '')
            ->distinct()
            ->pluck('church_group');

        // Create ChurchGroup records for each unique name
        foreach ($uniqueGroups as $groupName) {
            ChurchGroup::firstOrCreate(['name' => trim($groupName)]);
        }

        // Migrate member relationships
        $members = Member::whereNotNull('church_group')
            ->where('church_group', '!=', '')
            ->get();

        foreach ($members as $member) {
            $group = ChurchGroup::where('name', trim($member->church_group))->first();
            if ($group) {
                // Attach member to group (if not already attached)
                if (!$member->churchGroups()->where('church_group_id', $group->id)->exists()) {
                    $member->churchGroups()->attach($group->id);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all pivot records
        DB::table('church_group_member')->truncate();
        
        // Remove church groups that were created from migration
        // (Optional: you may want to keep them)
    }
};
