<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentExpiryReminder;
use Carbon\Carbon;
use App\Notifications\DocumentReminderNotification;

class SendReminderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email and in-app notifications for document reminders due today.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $reminders = DocumentExpiryReminder::where('reminder_date', $today)
            ->where('notified', false)
            ->with('employee', 'document')
            ->get();

        foreach ($reminders as $reminder) {
            $employee = $reminder->employee;

            $employee->notify(new DocumentReminderNotification($reminder));

            $reminder->update(['notified' => true]);

            $this->info("Notification sent to: {$employee->email}");
        }
    }
}
