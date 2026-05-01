<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Ambil notifikasi untuk user yang sedang login dari tabel notifications.
     */
    public function getNotifications(int $limit = 10): array
    {
        $user = Auth::user();

        // Ambil data dari tabel notifications milik user
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take($limit)
            ->get();

        $notifs = [];

        foreach ($notifications as $notification) {
            $data = $notification->data;

            $notifs[] = [
                'id'    => $notification->id,
                'icon'  => $data['icon'] ?? 'bi-bell',
                'color' => $data['color'] ?? 'text-primary',
                'title' => $data['title'] ?? 'Notifikasi Baru',
                'body'  => $data['body'] ?? '',
                'time'  => $notification->created_at->diffForHumans(),
                'read'  => $notification->read_at !== null,
                'url'   => $data['url'] ?? '#',
            ];
        }

        return $notifs;
    }

    /**
     * Trigger untuk membuat notifikasi baru (Gunakan ini di Service lain saat Create/Approve)
     */
    public function send($userId, $notifiable, array $displayData): Notification
    {
        return Notification::create([
            'user_id'         => $userId,
            'notifiable_id'   => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => [
                'icon'  => $displayData['icon'],
                'color' => $displayData['color'],
                'title' => $displayData['title'],
                'body'  => $displayData['body'],
                'url'   => $displayData['url'],
            ],
        ]);
    }

    /**
     * Hitung notifikasi yang belum dibaca (unread count).
     */
    public function countUnread(): int
    {
        return Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Tandai semua sebagai dibaca.
     */
    public function markAllAsRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
