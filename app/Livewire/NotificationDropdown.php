<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $notifications;
    public $unreadCount;

    protected $listeners = ['notificationReceived' => 'fetchNotifications'];

    public function mount()
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        $user = Auth::user();
        $this->notifications = $user->unreadNotifications()->take(5)->get();
        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        $this->fetchNotifications();
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
