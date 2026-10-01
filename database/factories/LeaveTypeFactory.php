<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Sick Leave', 'Personal Leave', 'Emergency Leave', 'Bereavement Leave', 'Study Leave',
        ]);

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(),
            'default_days' => $this->faker->numberBetween(3, 14),
            'requires_approval' => true,
            'is_active' => true,
        ];
    }
}
