<?php

namespace App\Notifications;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonitorStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Monitor $monitor,
        public MonitorStatus $oldStatus,
        public MonitorStatus $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = "Monitor Status Changed: {$this->monitor->name} is now {$this->newStatus->value}";
        $greeting = "Hello {$notifiable->name},";
        $line = "The status of your monitor '{$this->monitor->name}' ({$this->monitor->url}) has changed from **{$this->oldStatus->value}** to **{$this->newStatus->value}**.";

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line);

        if ($this->newStatus === MonitorStatus::Down) {
            $lastCheck = $this->monitor->checkLogs()->latest()->first();
            if ($lastCheck && $lastCheck->error_message) {
                $message->line('**Reason:** ' . $lastCheck->error_message);
            }
        }

        $message->action('View Monitor', url('/admin/monitors/' . $this->monitor->id));

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'monitor_id' => $this->monitor->id,
            'monitor_name' => $this->monitor->name,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
        ];
    }
}