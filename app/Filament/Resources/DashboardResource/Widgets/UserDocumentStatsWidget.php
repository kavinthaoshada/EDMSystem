<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\DocumentExpiryReminder;
use DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\Document;

class UserDocumentStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $totalUsers = User::count();
        $hrCount = User::where('role', 'hr')->count();
        $employeeCount = User::where('role', 'employee')->count();

        $totalNotifications = DB::table('notifications')->count();
        $unreadNotifications = DB::table('notifications')->whereNull('read_at')->count();

        $unnotifiedReminders = DocumentExpiryReminder::where('notified', false)->count();

        return [
            Card::make('Total Users', $totalUsers)
                ->description("HR: $hrCount | Employees: $employeeCount")
                ->color('primary')
                ->icon('heroicon-o-users'),

            Card::make('Total Documents', Document::count())
                ->description('All uploaded documents')
                ->color('warning')
                ->icon('heroicon-o-document-text'),

            Card::make('Total Notifications', $totalNotifications)
                ->description("Unread: $unreadNotifications | Total: $totalNotifications")
                ->color('info')
                ->icon('heroicon-o-bell'),
                
            Card::make('Pending Reminders', $unnotifiedReminders)
                ->description('Unnotified document expiry reminders')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle'),
        ];
    }
}
