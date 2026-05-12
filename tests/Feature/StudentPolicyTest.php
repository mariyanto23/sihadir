<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use App\Support\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role as SpatieRole;
use Tests\TestCase;

class StudentPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([Role::ADMIN, Role::STUDENT, Role::PARENT] as $role) {
            SpatieRole::firstOrCreate(['name' => $role]);
        }
    }

    public function test_parent_only_sees_connected_child(): void
    {
        $class = ClassRoom::factory()->create(['name' => '2A', 'level' => 2]);
        $child = Student::factory()->create(['class_id' => $class->id]);
        $otherChild = Student::factory()->create(['class_id' => $class->id]);
        $parent = User::factory()->create();
        $parent->assignRole(Role::PARENT);
        $parent->children()->attach($child);

        $this->actingAs($parent)->get(route('parent.children.show', $child))->assertOk();
        $this->actingAs($parent)->get(route('parent.children.show', $otherChild))->assertForbidden();
    }
}
