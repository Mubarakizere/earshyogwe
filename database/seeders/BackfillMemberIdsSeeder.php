<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Church;
use Illuminate\Database\Seeder;

class BackfillMemberIdsSeeder extends Seeder
{
    /**
     * Backfill member_id for all existing members that don't have one.
     * Uses the member's created_at year for the year portion of the ID.
     */
    public function run(): void
    {
        $members = Member::whereNull('member_id')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $count = 0;

        foreach ($members as $member) {
            $year = $member->created_at ? $member->created_at->format('Y') : date('Y');
            $memberId = Member::generateMemberId($member->church_id, $year);

            $member->update(['member_id' => $memberId]);
            $count++;
        }

        $this->command->info("✅ Backfilled {$count} member IDs successfully.");
    }
}
