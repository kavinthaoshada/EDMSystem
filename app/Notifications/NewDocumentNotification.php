<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDocumentNotification extends Notification
{
    use Queueable;

    protected $document;
    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Document Uploaded')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("A new document '{$this->document->document_name}' has been uploaded for you.")
            ->line("Category: {$this->document->category->category_name}")
            ->line("Expiry Date: " . ($this->document->expiry_date ?? 'No Expiry'))
            ->action('View Document', url('/storage/' . $this->document->file_path))
            ->line('Thank you for using our system!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "A new document '{$this->document->document_name}' has been uploaded.",
            'document_id' => $this->document->id,
            'file_path' => $this->document->file_path,
            'category' => $this->document->category->category_name,
            'expiry_date' => $this->document->expiry_date,
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
