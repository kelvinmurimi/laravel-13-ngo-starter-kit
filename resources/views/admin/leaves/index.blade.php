<x-layouts::app>
    <div class="mb-6">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Leave Requests
        </h2>
    </div>

    <div class="py-8">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4 flex gap-2 text-sm">
                @foreach (['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
                    <a href="{{ route('admin.leaves.index', array_filter(['status' => $value])) }}"
                       class="rounded-md px-3 py-1 {{ request('status', '') === $value ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Volunteer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($leaveRequests as $leave)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $leave->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->leaveType->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leave->start_date->format('M j, Y') }} – {{ $leave->end_date->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span @class([
                                        'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                        'bg-yellow-100 text-yellow-800' => $leave->status === 'pending',
                                        'bg-green-100 text-green-800' => $leave->status === 'approved',
                                        'bg-red-100 text-red-800' => $leave->status === 'rejected',
                                        'bg-gray-100 text-gray-800' => $leave->status === 'cancelled',
                                    ])>
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('admin.leaves.show', $leave) }}" class="text-indigo-600 hover:underline">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No leave requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</x-layouts::app>
