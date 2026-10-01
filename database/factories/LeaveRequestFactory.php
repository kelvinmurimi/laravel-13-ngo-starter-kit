<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+2 weeks');
        $end = (clone $start)->modify('+' . $this->faker->numberBetween(0, 4) . ' days');

        return [
            'user_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'total_days' => LeaveRequest::calculateTotalDays($start->format('Y-m-d'), $end->format('Y-m-d')),
            'reason' => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }

    public function approved(): self
    {
        return $this->state(fn () => [
            'status' => 'approved',
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
            'review_notes' => 'Not enough coverage that week.',
        ]);
    }
}
