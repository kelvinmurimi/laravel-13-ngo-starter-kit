<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\HasTeams;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'current_team_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasTeams, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

     // Define the relationship with the Program model
     public function programs()
     {
         return $this->hasMany(Program::class); 
     }
        // Define the relationship with the Partner model
        public function partners()
        {
            return $this->hasMany(Partner::class); 
        }
        // Define the relationship with the Volunteer model
        public function volunteers()
        {
            return $this->hasMany(Volunteer::class);    
        }
        // Define the relationship with the Staff model
        public function staff()
        {
            return $this->hasMany(Staff::class);
        }
// Define the relationship with the Blog model
        public function blogs()
        {
            return $this->hasMany(Blog::class);    
        }
        // Define the relationship with the Event model
        public function events()
        {
            return $this->hasMany(Event::class);    
        }
        // Define the relationship with the Team model

        // Define the relationship with the Publication model
        public function publications()
        {
            return $this->hasMany(Publication::class);
        }







    // --- Leave management ---
    public function leaveRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function isVolunteer(): bool
    {
        return $this->volunteers()->exists();
    }

    public function isStaffMember(): bool
    {
        return $this->staff()->exists();
    }
}
