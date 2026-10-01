<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Sick Leave', 'default_days' => 10],
            ['name' => 'Personal Leave', 'default_days' => 5],
            ['name' => 'Emergency Leave', 'default_days' => 3],
            ['name' => 'Bereavement Leave', 'default_days' => 5],
        ];

        foreach ($types as $type) {
            LeaveType::firstOrCreate(
                ['slug' => Str::slug($type['name'])],
                [
                    'name' => $type['name'],
                    'default_days' => $type['default_days'],
                    'requires_approval' => true,
                    'is_active' => true,
                ]
            );
        }
    }
}
