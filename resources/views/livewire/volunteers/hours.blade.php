<div class="space-y-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Volunteer Hours') }}</flux:heading>
            <flux:subheading>{{ __('Log and review volunteer time.') }}</flux:subheading>
        </div>
        <flux:badge color="blue">{{ __('Total') }}: {{ number_format($totalHours, 1) }} h</flux:badge>
    </div>

    <flux:card>
        <form wire:submit="save" class="grid gap-4 md:grid-cols-2">
            <flux:select wire:model="volunteer_member_id" label="Volunteer" placeholder="Select volunteer">
                @foreach ($members as $m)
                    <flux:select.option value="{{ $m->id }}">{{ $m->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model="worked_on" type="date" label="Date" />
            <flux:input wire:model="hours" type="number" step="0.25" min="0.25" max="24" label="Hours" />
            <flux:input wire:model="activity" label="Activity" placeholder="e.g. Food drive" />
            <div class="md:col-span-2">
                <flux:textarea wire:model="notes" label="Notes (optional)" rows="2" />
            </div>
            <div class="md:col-span-2">
                <flux:button type="submit" variant="primary" color="blue">{{ __('Log Hours') }}</flux:button>
            </div>
        </form>
    </flux:card>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Date') }}</flux:table.column>
            <flux:table.column>{{ __('Volunteer') }}</flux:table.column>
            <flux:table.column>{{ __('Activity') }}</flux:table.column>
            <flux:table.column>{{ __('Hours') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($logs as $log)
                <flux:table.row :key="$log->id">
                    <flux:table.cell>{{ $log->worked_on->format('d M Y') }}</flux:table.cell>
                    <flux:table.cell>{{ $log->member->name }}</flux:table.cell>
                    <flux:table.cell>{{ $log->activity }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($log->hours, 2) }}</flux:table.cell>
                    <flux:table.cell class="flex justify-end">
                        <flux:button size="sm" variant="danger" wire:click="delete({{ $log->id }})" wire:confirm="Delete this entry?">{{ __('Delete') }}</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row><flux:table.cell colspan="5">{{ __('No hours logged yet.') }}</flux:table.cell></flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
