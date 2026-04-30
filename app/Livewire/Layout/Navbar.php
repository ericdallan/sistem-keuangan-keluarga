<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use App\Services\NotificationService;
use Livewire\Component;

class Navbar extends Component
{
    public string $title = '';
    public bool $notifOpen = false;

    protected function getNotificationService(): NotificationService
    {
        return app(NotificationService::class);
    }

    public function markAllRead(): void
    {
        $this->getNotificationService()->markAllAsRead();
    }

    public function markRead(int $id): void
    {
        \App\Models\Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['read_at' => now()]);

        $notification = \App\Models\Notification::find($id);
        if ($notification && isset($notification->data['url'])) {
            $this->redirect($notification->data['url'], navigate: true);
        }
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        $service = $this->getNotificationService();

        return view('livewire.layout.navbar', [
            'notifications' => $service->getNotifications(10),
            'unreadCount' => $service->countUnread(),
        ]);
    }
}
