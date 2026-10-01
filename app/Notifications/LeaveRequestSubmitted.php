<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $volunteer = $this->leaveRequest->user;

        return (new MailMessage)
            ->subject('New leave request from ' . $volunteer->name)
            ->line($volunteer->name . ' has requested ' . $this->leaveRequest->total_days . ' day(s) of ' . $this->leaveRequest->leaveType->name . ' leave.')
            ->line('Dates: ' . $this->leaveRequest->start_date->format('M j, Y') . ' – ' . $this->leaveRequest->end_date->format('M j, Y'))
            ->action('Review request', route('admin.leaves.show', $this->leaveRequest))
            ->line('Please review and respond at your earliest convenience.');
    }

    public function toArray($notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'volunteer_name' => $this->leaveRequest->user->name,
            'message' => $this->leaveRequest->user->name . ' submitted a leave request.',
        ];
    }
}
