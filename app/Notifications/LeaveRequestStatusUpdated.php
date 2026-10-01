<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusUpdated extends Notification implements ShouldQueue
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
        $status = ucfirst($this->leaveRequest->status);

        $mail = (new MailMessage)
            ->subject('Your leave request was ' . strtolower($status))
            ->line('Your ' . $this->leaveRequest->leaveType->name . ' leave request for ' . $this->leaveRequest->start_date->format('M j, Y') . ' – ' . $this->leaveRequest->end_date->format('M j, Y') . ' has been ' . strtolower($status) . '.');

        if ($this->leaveRequest->review_notes) {
            $mail->line('Note from reviewer: ' . $this->leaveRequest->review_notes);
        }

        return $mail->action('View request', route('volunteer.leaves.show', $this->leaveRequest));
    }

    public function toArray($notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
            'message' => 'Your leave request was ' . $this->leaveRequest->status . '.',
        ];
    }
}
