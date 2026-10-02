<?php

namespace App\Livewire\Volunteers;

use App\Models\VolunteerMember;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $skills = '';
    public string $availability = '';
    public string $status = 'active';
    public string $joined_on = '';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('volunteer_members', 'email')->ignore($this->editingId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'skills' => ['nullable', 'string', 'max:2000'],
            'availability' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'joined_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function edit(int $id): void
    {
        $m = VolunteerMember::findOrFail($id);
        $this->resetValidation();

        $this->editingId = $m->id;
        $this->name = $m->name;
        $this->email = $m->email ?? '';
        $this->phone = $m->phone ?? '';
        $this->skills = $m->skills ?? '';
        $this->availability = $m->availability ?? '';
        $this->status = $m->status;
        $this->joined_on = $m->joined_on?->format('Y-m-d') ?? '';
        $this->notes = $m->notes ?? '';
    }

    public function save(): void
    {
        $data = array_map(fn ($v) => $v === '' ? null : $v, $this->validate());

        VolunteerMember::updateOrCreate(['id' => $this->editingId], $data);

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        VolunteerMember::findOrFail($id)->delete();
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'name', 'email', 'phone', 'skills', 'availability', 'joined_on', 'notes']);
        $this->status = 'active';
    }

    public function render()
    {
        $members = VolunteerMember::query()
            ->withSum('hours as total_hours', 'hours')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('skills', 'like', "%{$this->search}%");
            }))
            ->orderBy('name')
            ->get();

        return view('livewire.volunteers.index', compact('members'));
    }
}
