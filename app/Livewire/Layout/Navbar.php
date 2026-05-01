<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use App\Services\NotificationService;
use Livewire\Component;

/**
 * Komponen Livewire untuk Navbar aplikasi.
 * Menangani fungsi logout, manajemen notifikasi, dan status pembacaan notifikasi.
 */
class Navbar extends Component
{
    // ── State ────────────────────────────────────────────────────
    public string $title = '';
    public bool $notifOpen = false; // Status visibilitas dropdown notifikasi

    /**
     * Helper untuk mendapatkan instance NotificationService dari container.
     */
    protected function getNotificationService(): NotificationService
    {
        return app(NotificationService::class);
    }

    /**
     * Menandai seluruh notifikasi pengguna sebagai sudah dibaca.
     */
    public function markAllRead(): void
    {
        $this->getNotificationService()->markAllAsRead();
    }

    /**
     * Menandai notifikasi spesifik sebagai sudah dibaca dan melakukan redirect.
     * Menggunakan pengecekan kepemilikan untuk keamanan (user_id).
     */
    public function markRead(int $id): void
    {
        \App\Models\Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['read_at' => now()]);

        // Setelah ditandai dibaca, arahkan pengguna ke URL tujuan jika ada
        $notification = \App\Models\Notification::find($id);
        if ($notification && isset($notification->data['url'])) {
            $this->redirect($notification->data['url'], navigate: true);
        }
    }

    /**
     * Menangani proses logout pengguna.
     */
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    /**
     * Merender navbar dengan data notifikasi terbaru.
     */
    public function render()
    {
        $service = $this->getNotificationService();

        return view('livewire.layout.navbar', [
            'notifications' => $service->getNotifications(10), // Mengambil 10 notifikasi terbaru
            'unreadCount'   => $service->countUnread(),         // Menghitung jumlah notifikasi yang belum dibaca
        ]);
    }
}
