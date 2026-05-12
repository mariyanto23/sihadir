<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 6) as $level) {
            foreach (['A', 'B'] as $group) {
                ClassRoom::query()->firstOrCreate([
                    'name' => $level.$group,
                ], [
                    'level' => $level,
                ]);
            }
        }
    }
}
