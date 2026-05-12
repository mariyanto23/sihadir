<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use App\Support\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role as SpatieRole;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([Role::ADMIN, Role::STUDENT, Role::PARENT] as $role) {
            SpatieRole::firstOrCreate(['name' => $role]);
        }
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_student_and_parent_cannot_access_admin_dashboard(): void
    {
        $class = ClassRoom::factory()->create(['name' => '1A', 'level' => 1]);
        $studentUser = User::factory()->create();
        $studentUser->assignRole(Role::STUDENT);
        Student::factory()->create(['class_id' => $class->id, 'user_id' => $studentUser->id]);

        $parent = User::factory()->create();
        $parent->assignRole(Role::PARENT);

        $this->actingAs($studentUser)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($parent)->get(route('admin.dashboard'))->assertForbidden();
    }
}
