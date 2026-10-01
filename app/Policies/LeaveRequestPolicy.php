<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    /** Staff can see everything; volunteers can only see their own. */
    public function viewAny(User $user): bool
    {
        return $user->isStaffMember() || $user->isVolunteer();
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->isStaffMember()) {
            return true;
        }

        return $user->id === $leaveRequest->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isVolunteer();
    }

    /** Volunteers may cancel only their own pending requests. */
    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id && $leaveRequest->isPending();
    }

    public function review(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->isStaffMember() && $leaveRequest->isPending();
    }
}
