<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Notifications\LeaveRequestStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LeaveRequest::class);

        $leaveRequests = LeaveRequest::query()
            ->with(['user', 'leaveType'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.leaves.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leave): View
    {
        $this->authorize('view', $leave);

        return view('admin.leaves.show', ['leaveRequest' => $leave->load('user', 'leaveType', 'reviewer')]);
    }

    public function approve(Request $request, LeaveRequest $leave): RedirectResponse
    {
        $this->authorize('review', $leave);

        $validated = $request->validate(['review_notes' => ['nullable', 'string', 'max:1000']]);

        $leave->approve($request->user(), $validated['review_notes'] ?? null);
        $leave->user->notify(new LeaveRequestStatusUpdated($leave));

        return back()->with('status', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leave): RedirectResponse
    {
        $this->authorize('review', $leave);

        $validated = $request->validate(['review_notes' => ['required', 'string', 'max:1000']]);

        $leave->reject($request->user(), $validated['review_notes']);
        $leave->user->notify(new LeaveRequestStatusUpdated($leave));

        return back()->with('status', 'Leave request rejected.');
    }
}
