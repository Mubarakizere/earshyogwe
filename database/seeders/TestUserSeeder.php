<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Church;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample churches
        $church1 = Church::create([
            'name' => 'St. Peter Cathedral',
            'location' => 'Kigali',
            'address' => 'KN 5 Ave, Kigali',
            'phone' => '+250788123456',
            'email' => 'stpeter@church.rw',
            'diocese' => 'Kigali Diocese',
            'region' => 'Central',
            'is_active' => true,
        ]);

        $church2 = Church::create([
            'name' => 'Holy Trinity Church',
            'location' => 'Musanze',
            'address' => 'Musanze District',
            'phone' => '+250788234567',
            'email' => 'trinity@church.rw',
            'diocese' => 'Kigali Diocese',
            'region' => 'Northern',
            'is_active' => true,
        ]);

        $church3 = Church::create([
            'name' => 'Grace Community Church',
            'location' => 'Huye',
            'address' => 'Huye District',
            'phone' => '+250788345678',
            'email' => 'grace@church.rw',
            'diocese' => 'Kigali Diocese',
            'region' => 'Southern',
            'is_active' => true,
        ]);

        // Create Boss (Diocese Administrator)
        $boss = User::create([
            'name' => 'Bishop John Doe',
            'email' => 'boss@church.rw',
            'password' => Hash::make('password'),
            'church_id' => null, // Boss oversees all churches
        ]);
        $boss->assignRole('boss');

        // Create Archid (Regional Supervisor)
        $archid = User::create([
            'name' => 'Archdeacon Jane Smith',
            'email' => 'archid@church.rw',
            'password' => Hash::make('password'),
            'church_id' => null, // Archid manages multiple churches
        ]);
        $archid->assignRole('archid');

        // Assign churches to archid
        $church1->update(['archid_id' => $archid->id]);
        $church2->update(['archid_id' => $archid->id]);
        $church3->update(['archid_id' => $archid->id]);

        // Create Pastors
        $pastor1 = User::create([
            'name' => 'Pastor David Wilson',
            'email' => 'pastor1@church.rw',
            'password' => Hash::make('password'),
            'church_id' => $church1->id,
        ]);
        $pastor1->assignRole('pastor');
        $church1->update(['pastor_id' => $pastor1->id]);

        $pastor2 = User::create([
            'name' => 'Pastor Sarah Johnson',
            'email' => 'pastor2@church.rw',
            'password' => Hash::make('password'),
            'church_id' => $church2->id,
        ]);
        $pastor2->assignRole('pastor');
        $church2->update(['pastor_id' => $pastor2->id]);

        $pastor3 = User::create([
            'name' => 'Pastor Michael Brown',
            'email' => 'pastor3@church.rw',
            'password' => Hash::make('password'),
            'church_id' => $church3->id,
        ]);
        $pastor3->assignRole('pastor');
        $church3->update(['pastor_id' => $pastor3->id]);

        // Create departments for each church
        $departments = ['Finance', 'Youth', 'Evangelism', 'Worship', 'Children Ministry'];
        
        foreach ([$church1, $church2, $church3] as $church) {
            foreach ($departments as $deptName) {
                Department::create([
                    'church_id' => $church->id,
                    'name' => $deptName,
                    'description' => "{$deptName} department for {$church->name}",
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Test users and churches created successfully!');
        $this->command->info('Boss: boss@church.rw / password');
        $this->command->info('Archid: archid@church.rw / password');
        $this->command->info('Pastor 1: pastor1@church.rw / password');
        $this->command->info('Pastor 2: pastor2@church.rw / password');
        $this->command->info('Pastor 3: pastor3@church.rw / password');
    }
}
