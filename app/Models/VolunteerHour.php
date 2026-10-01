<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerHour extends Model
{
    protected $fillable = ['volunteer_member_id', 'worked_on', 'hours', 'activity', 'notes'];

    protected $casts = [
        'worked_on' => 'date',
        'hours' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(VolunteerMember::class, 'volunteer_member_id');
    }
}
