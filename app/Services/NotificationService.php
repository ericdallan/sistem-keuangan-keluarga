<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Service untuk mengelola sistem notifikasi user.
 * Bertanggung jawab atas pengambilan, pengiriman, dan manajemen status notifikasi.
 */
class NotificationService
{
    /**
     * Mengambil daftar notifikasi untuk user yang sedang login.
     * Mengembalikan array format untuk kemudahan tampilan di view.
     */
    public function getNotifications(int $limit = 10): array
    {
        $user = Auth::user();

        // Ambil notifikasi terbaru milik user
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
                'time'  => $notification->created_at->diffForHumans(), // Format waktu ramah user
                'read'  => $notification->read_at !== null,
                'url'   => $data['url'] ?? '#',
            ];
        }

        return $notifs;
    }

    /**
     * Membuat notifikasi baru.
     * Gunakan metode ini di Service lain (Expense/FundRequest) agar kode lebih bersih.
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
     * Menghitung jumlah notifikasi yang belum dibaca (unread).
     * Sangat berguna untuk menampilkan angka di badge notifikasi.
     */
    public function countUnread(): int
    {
        return Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Menandai semua notifikasi milik user menjadi "sudah dibaca".
     */
    public function markAllAsRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
