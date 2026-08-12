<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Application $application, public string $action, public ?string $remarks = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $messages = [
            'forward' => 'Your application ' . $this->application->application_no . ' has moved to the next review step.',
            'backward' => 'Your application ' . $this->application->application_no . ' was sent back for revision.',
            'approve' => 'Your application ' . $this->application->application_no . ' has been approved.',
            'reject' => 'Your application ' . $this->application->application_no . ' has been rejected.',
        ];

        return [
            'application_id' => $this->application->id,
            'application_no' => $this->application->application_no,
            'action' => $this->action,
            'message' => $messages[$this->action] ?? 'Your application status has changed.',
            'remarks' => $this->remarks,
        ];
    }
}
