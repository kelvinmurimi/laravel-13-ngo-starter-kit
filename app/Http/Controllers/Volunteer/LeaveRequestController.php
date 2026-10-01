<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\LeaveRequestSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LeaveRequest::class);

        $leaveRequests = $request->user()
            ->leaveRequests()
            ->with('leaveType')
            ->latest()
            ->paginate(10);

        return view('volunteer.leaves.index', compact('leaveRequests'));
    }

    public function create(): View
    {
        $this->authorize('create', LeaveRequest::class);

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('volunteer.leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', LeaveRequest::class);

        $validated = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $leaveRequest = $request->user()->leaveRequests()->create([
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => LeaveRequest::calculateTotalDays($validated['start_date'], $validated['end_date']),
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Notify staff/admins. Swap this query for however the app already
        // resolves "reviewers" if there's an existing helper for it.
        $reviewers = User::whereHas('staff')->get();
        foreach ($reviewers as $reviewer) {
            $reviewer->notify(new LeaveRequestSubmitted($leaveRequest));
        }

        return redirect()
            ->route('volunteer.leaves.index')
            ->with('status', 'Leave request submitted for review.');
    }

    public function show(LeaveRequest $leave): View
    {
        $this->authorize('view', $leave);

        return view('volunteer.leaves.show', ['leaveRequest' => $leave->load('leaveType', 'reviewer')]);
    }

    public function cancel(LeaveRequest $leave): RedirectResponse
    {
        $this->authorize('cancel', $leave);

        $leave->update(['status' => 'cancelled']);

        return redirect()
            ->route('volunteer.leaves.index')
            ->with('status', 'Leave request cancelled.');
    }
}
