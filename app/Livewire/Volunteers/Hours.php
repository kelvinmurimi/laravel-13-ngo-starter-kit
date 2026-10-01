<?php

namespace App\Livewire\Volunteers;

use App\Models\VolunteerHour;
use App\Models\VolunteerMember;
use Livewire\Component;

class Hours extends Component
{
    public string $volunteer_member_id = '';
    public string $worked_on = '';
    public string $hours = '';
    public string $activity = '';
    public string $notes = '';

    public function mount(): void
    {
        $this->worked_on = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'volunteer_member_id' => ['required', 'exists:volunteer_members,id'],
            'worked_on' => ['required', 'date', 'before_or_equal:today'],
            'hours' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'activity' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function save(): void
    {
        $data = array_map(fn ($v) => $v === '' ? null : $v, $this->validate());

        VolunteerHour::create($data);

        $this->reset(['hours', 'activity', 'notes']);
    }

    public function delete(int $id): void
    {
        VolunteerHour::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.volunteers.hours', [
            'members' => VolunteerMember::where('status', 'active')->orderBy('name')->get(),
            'logs' => VolunteerHour::with('member')->latest('worked_on')->latest('id')->limit(50)->get(),
            'totalHours' => VolunteerHour::sum('hours'),
        ]);
    }
}
