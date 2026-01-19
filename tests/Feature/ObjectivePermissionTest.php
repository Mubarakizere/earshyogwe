<?php

namespace Tests\Feature;

use App\Models\Church;
use App\Models\Department;
use App\Models\Objective;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ObjectivePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_head_can_view_their_department_objectives()
    {
        // 1. Setup
        $church = Church::create(['name' => 'Test Church', 'is_active' => true]);
        $head = User::factory()->create(['church_id' => $church->id]);
        
        $dept = Department::create([
            'name' => 'Finance',
            'slug' => 'finance',
            'head_id' => $head->id,
            'is_active' => true,
        ]);

        $otherDept = Department::create([
            'name' => 'HR',
            'slug' => 'hr',
            'is_active' => true,
        ]);

        $objectiveFinance = Objective::create([
            'department_id' => $dept->id,
            'church_id' => $church->id,
            'name' => 'Finance Objective',
            'target' => 100,
            'start_date' => now(),
            'status' => 'planned',
            'priority_level' => 'medium',
            'tracking_frequency' => 'monthly',
            'created_by' => $head->id,
        ]);

        $objectiveHR = Objective::create([
            'department_id' => $otherDept->id,
            'church_id' => $church->id,
            'name' => 'HR Objective',
            'target' => 100,
            'start_date' => now(),
            'status' => 'planned',
            'priority_level' => 'medium',
            'tracking_frequency' => 'monthly',
            'created_by' => $head->id,
        ]);

        // 2. Head should have permission via boot logic
        $permissionName = $dept->permission_name;
        $this->assertTrue($head->hasPermissionTo($permissionName));

        // 3. Act as head
        $this->actingAs($head);

        // 4. Verify Index
        $response = $this->get(route('objectives.index'));
        $response->assertStatus(200);
        $response->assertSee('Finance Objective');
        $response->assertDontSee('HR Objective');

        // 5. Verify Show (Allowed)
        $response = $this->get(route('objectives.show', $objectiveFinance));
        $response->assertStatus(200);

        // 6. Verify Show (Denied)
        $response = $this->get(route('objectives.show', $objectiveHR));
        $response->assertStatus(403);
    }

    public function test_boss_can_view_all_objectives()
    {
        $boss = User::factory()->create();
        $role = \Spatie\Permission\Models\Role::create(['name' => 'boss']);
        Permission::create(['name' => 'view all objectives']);
        $role->givePermissionTo('view all objectives');
        $boss->assignRole($role);

        $dept = Department::create(['name' => 'Finance', 'slug' => 'finance']);
        $objective = Objective::create([
            'department_id' => $dept->id,
            'name' => 'Any Objective',
            'target' => 100,
            'start_date' => now(),
            'status' => 'planned',
            'priority_level' => 'medium',
            'tracking_frequency' => 'monthly',
            'created_by' => $boss->id,
        ]);

        $this->actingAs($boss);
        $response = $this->get(route('objectives.index'));
        $response->assertSee($objective->name);

        $response = $this->get(route('objectives.show', $objective));
        $response->assertStatus(200);
    }
}
