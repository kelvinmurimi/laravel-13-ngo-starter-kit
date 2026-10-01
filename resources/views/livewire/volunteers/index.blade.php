<div class="space-y-6 p-6">
    <div>
        <flux:heading size="xl">{{ __('Volunteers') }}</flux:heading>
        <flux:subheading>{{ __('Manage your volunteer directory.') }}</flux:subheading>
    </div>

    <flux:card>
        <form wire:submit="save" class="grid gap-4 md:grid-cols-3">
            <flux:input wire:model="name" label="Name" required />
            <flux:input wire:model="email" type="email" label="Email" />
            <flux:input wire:model="phone" label="Phone" />
            <flux:input wire:model="availability" label="Availability" placeholder="e.g. Weekends" />
            <flux:input wire:model="joined_on" type="date" label="Joined on" />
            <flux:select wire:model="status" label="Status">
                <flux:select.option value="active">Active</flux:select.option>
                <flux:select.option value="inactive">Inactive</flux:select.option>
            </flux:select>
            <div class="md:col-span-3">
                <flux:textarea wire:model="skills" label="Skills" rows="2" />
            </div>
            <div class="md:col-span-3">
                <flux:textarea wire:model="notes" label="Notes" rows="2" />
            </div>
            <div class="flex gap-2 md:col-span-3">
                <flux:button type="submit" variant="primary" color="blue">{{ $editingId ? __('Update') : __('Add') }}</flux:button>
                @if ($editingId)
                    <flux:button type="button" variant="ghost" wire:click="resetForm">{{ __('Cancel') }}</flux:button>
                @endif
            </div>
        </form>
    </flux:card>

    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search name, email or skills" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Contact') }}</flux:table.column>
            <flux:table.column>{{ __('Skills') }}</flux:table.column>
            <flux:table.column>{{ __('Hours') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($members as $m)
                <flux:table.row :key="$m->id">
                    <flux:table.cell>{{ $m->name }}</flux:table.cell>
                    <flux:table.cell>{{ $m->email }} {{ $m->phone }}</flux:table.cell>
                    <flux:table.cell>{{ \Illuminate\Support\Str::limit($m->skills, 40) }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($m->total_hours ?? 0, 1) }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" :color="$m->status === 'active' ? 'green' : 'zinc'">{{ ucfirst($m->status) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="flex gap-2 justify-end">
                        <flux:button size="sm" wire:click="edit({{ $m->id }})">{{ __('Edit') }}</flux:button>
                        <flux:button size="sm" variant="danger" wire:click="delete({{ $m->id }})" wire:confirm="Delete this volunteer and their logged hours?">{{ __('Delete') }}</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row><flux:table.cell colspan="6">{{ __('No volunteers found.') }}</flux:table.cell></flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
