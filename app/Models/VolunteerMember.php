<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerMember extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'skills', 'availability',
        'status', 'joined_on', 'notes',
    ];

    protected $casts = [
        'joined_on' => 'date',
    ];

    public function hours(): HasMany
    {
        return $this->hasMany(VolunteerHour::class);
    }
}
