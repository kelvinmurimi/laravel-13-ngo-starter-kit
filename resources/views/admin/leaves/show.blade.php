<x-layouts::app>
    <div class="mb-6">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Review Leave Request
        </h2>
    </div>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 space-y-6">

            <div class="space-y-4 bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Volunteer</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Type</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->leaveType->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Dates</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            {{ $leaveRequest->start_date->format('M j, Y') }} – {{ $leaveRequest->end_date->format('M j, Y') }}
                            ({{ $leaveRequest->total_days }} day(s))
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ ucfirst($leaveRequest->status) }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Reason</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $leaveRequest->reason }}</dd>
                    </div>
                </dl>
            </div>

            @if ($leaveRequest->isPending())
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('admin.leaves.approve', $leaveRequest) }}" class="space-y-2 bg-white p-4 shadow-sm sm:rounded-lg dark:bg-gray-800">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (optional)</label>
                        <textarea name="review_notes" rows="2" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"></textarea>
                        <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.leaves.reject', $leaveRequest) }}" class="space-y-2 bg-white p-4 shadow-sm sm:rounded-lg dark:bg-gray-800">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for rejection</label>
                        <textarea name="review_notes" rows="2" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"></textarea>
                        <button type="submit" class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Reject
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
