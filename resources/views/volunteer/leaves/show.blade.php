<x-layouts::app>
    <div class="mb-6">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Leave Request — {{ $leaveRequest->leaveType->name }}
        </h2>
    </div>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="space-y-4 bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ ucfirst($leaveRequest->status) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Days</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->total_days }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Start date</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->start_date->format('M j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">End date</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->end_date->format('M j, Y') }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Reason</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->reason }}</dd>
                    </div>

                    @if ($leaveRequest->reviewer)
                        <div class="col-span-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Reviewed by</dt>
                            <dd class="text-gray-900 dark:text-gray-100">
                                {{ $leaveRequest->reviewer->name }} on {{ $leaveRequest->reviewed_at->format('M j, Y') }}
                            </dd>
                        </div>
                    @endif

                    @if ($leaveRequest->review_notes)
                        <div class="col-span-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Reviewer notes</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->review_notes }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($leaveRequest->isPending())
                    <form method="POST" action="{{ route('volunteer.leaves.cancel', $leaveRequest) }}"
                          onsubmit="return confirm('Cancel this leave request?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-sm font-medium text-red-600 hover:underline">
                            Cancel request
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
