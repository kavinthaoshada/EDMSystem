<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\DocumentExpiryReminder;

class DocumentReminderNotification extends Notification
{
    use Queueable;
    protected $reminder;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentExpiryReminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Your Document is Due Soon')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("This is a reminder for your document '{$this->reminder->document->document_name}'.")
            ->line("Reminder Date: {$this->reminder->reminder_date}")
            ->line("Please review your document before it expires on {$this->reminder->document->expiry_date}.")
            ->action('View Document', url('/storage/' . $this->reminder->document->file_path))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Reminder set for document '{$this->reminder->document->document_name}' on {$this->reminder->reminder_date}.",
            'document_id' => $this->reminder->document->id,
            'reminder_date' => $this->reminder->reminder_date,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
