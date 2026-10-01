<?php

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Staff;
use App\Models\User;
use App\Models\Volunteer;
use App\Notifications\LeaveRequestStatusUpdated;
use App\Notifications\LeaveRequestSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->leaveType = LeaveType::factory()->create();
});

function volunteer(): User
{
    $user = User::factory()->create();

    Volunteer::create([
        'name' => $user->name,
        'slug' => Str::slug($user->name.'-'.Str::random(6)),
        'role' => 'volunteer',
        'user_id' => $user->id,
        'image_url' => 'placeholder.jpg',
        'content' => 'Test volunteer',
    ]);

    return $user;
}

// $role is kept only so existing calls like staffMember('admin') still work.
function staffMember(string $role = 'admin'): User
{
    $user = User::factory()->create();

    Staff::create([
        'name' => $user->name,
        'slug' => Str::slug($user->name.'-'.Str::random(6)),
        'role' => $role,
        'user_id' => $user->id,
        'image_url' => 'placeholder.jpg',
        'content' => 'Test staff',
    ]);

    return $user;
}

// --- Volunteer: viewing & submitting ---

it('lets a volunteer view their own leave requests', function () {
    $volunteer = volunteer();
    LeaveRequest::factory()->for($volunteer)->for($this->leaveType)->create();

    $this->actingAs($volunteer)
        ->get(route('volunteer.leaves.index'))
        ->assertOk()
        ->assertSee($this->leaveType->name);
});

it('lets a volunteer submit a leave request and notifies staff', function () {
    Notification::fake();

    $volunteer = volunteer();
    $admin = staffMember('admin');

    $response = $this->actingAs($volunteer)->post(route('volunteer.leaves.store'), [
        'leave_type_id' => $this->leaveType->id,
        'start_date' => now()->addDays(3)->format('Y-m-d'),
        'end_date' => now()->addDays(5)->format('Y-m-d'),
        'reason' => 'Family commitment out of town.',
    ]);

    $response->assertRedirect(route('volunteer.leaves.index'));

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $volunteer->id,
        'leave_type_id' => $this->leaveType->id,
        'status' => 'pending',
        'total_days' => 3,
    ]);

    Notification::assertSentTo($admin, LeaveRequestSubmitted::class);
});

it('rejects a leave request with an end date before the start date', function () {
    $volunteer = volunteer();

    $this->actingAs($volunteer)->post(route('volunteer.leaves.store'), [
        'leave_type_id' => $this->leaveType->id,
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date' => now()->addDays(2)->format('Y-m-d'),
        'reason' => 'Invalid range.',
    ])->assertSessionHasErrors('end_date');
});

it('prevents a volunteer from viewing another volunteer\'s leave request', function () {
    $owner = volunteer();
    $other = volunteer();
    $leave = LeaveRequest::factory()->for($owner)->for($this->leaveType)->create();

    $this->actingAs($other)
        ->get(route('volunteer.leaves.show', $leave))
        ->assertForbidden();
});

// --- Volunteer: cancelling ---

it('lets a volunteer cancel their own pending request', function () {
    $volunteer = volunteer();
    $leave = LeaveRequest::factory()->for($volunteer)->for($this->leaveType)->create();

    $this->actingAs($volunteer)
        ->patch(route('volunteer.leaves.cancel', $leave))
        ->assertRedirect(route('volunteer.leaves.index'));

    expect($leave->fresh()->status)->toBe('cancelled');
});

it('prevents a volunteer from cancelling an already-approved request', function () {
    $volunteer = volunteer();
    $leave = LeaveRequest::factory()->approved()->for($volunteer)->for($this->leaveType)->create();

    $this->actingAs($volunteer)
        ->patch(route('volunteer.leaves.cancel', $leave))
        ->assertForbidden();

    expect($leave->fresh()->status)->toBe('approved');
});

// --- Admin/staff: reviewing ---

it('lets an admin view all leave requests', function () {
    $admin = staffMember('admin');
    LeaveRequest::factory()->for(volunteer())->for($this->leaveType)->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.leaves.index'))
        ->assertOk();
});

it('prevents a volunteer from accessing the admin review screen', function () {
    $volunteer = volunteer();
    $leave = LeaveRequest::factory()->for(volunteer())->for($this->leaveType)->create();

    $this->actingAs($volunteer)
        ->get(route('admin.leaves.show', $leave))
        ->assertForbidden();
});

it('lets an admin approve a pending request and notifies the volunteer', function () {
    Notification::fake();

    $admin = staffMember('admin');
    $volunteer = volunteer();
    $leave = LeaveRequest::factory()->for($volunteer)->for($this->leaveType)->create();

    $this->actingAs($admin)
        ->patch(route('admin.leaves.approve', $leave), ['review_notes' => 'Enjoy your time off.'])
        ->assertRedirect();

    $leave->refresh();
    expect($leave->status)->toBe('approved')
        ->and($leave->reviewed_by)->toBe($admin->id)
        ->and($leave->review_notes)->toBe('Enjoy your time off.');

    Notification::assertSentTo($volunteer, LeaveRequestStatusUpdated::class);
});

it('lets staff reject a pending request but requires notes', function () {
    $staff = staffMember('staff');
    $leave = LeaveRequest::factory()->for(volunteer())->for($this->leaveType)->create();

    $this->actingAs($staff)
        ->patch(route('admin.leaves.reject', $leave), [])
        ->assertSessionHasErrors('review_notes');

    $this->actingAs($staff)
        ->patch(route('admin.leaves.reject', $leave), ['review_notes' => 'Too many volunteers off that week.'])
        ->assertRedirect();

    expect($leave->fresh()->status)->toBe('rejected');
});

it('prevents re-reviewing a request that is no longer pending', function () {
    $admin = staffMember('admin');
    $leave = LeaveRequest::factory()->approved()->for(volunteer())->for($this->leaveType)->create();

    $this->actingAs($admin)
        ->patch(route('admin.leaves.approve', $leave), [])
        ->assertForbidden();
});

// --- Guests ---

it('redirects guests to login for volunteer leave routes', function () {
    $this->get(route('volunteer.leaves.index'))->assertRedirect(route('login'));
});

it('redirects guests to login for admin leave routes', function () {
    $this->get(route('admin.leaves.index'))->assertRedirect(route('login'));
});
