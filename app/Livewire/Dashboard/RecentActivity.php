<?php

namespace App\Livewire\Dashboard;

use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Komponen Livewire untuk menampilkan aktivitas terbaru di dashboard.
 * Menangani filter pencarian, tipe aktivitas, dan filter bulanan secara dinamis.
 */
class RecentActivity extends Component
{
    use WithPagination;

    // ── State (Filter Data) ──────────────────────────────────────
    public string $search  = '';
    public string $type    = '';
    public string $month   = '';
    public int    $perPage = 10;

    /**
     * Hook Livewire: Reset halaman ke-1 setiap kali filter diubah.
     * Mencegah bug pagination saat hasil pencarian kurang dari jumlah halaman saat ini.
     */
    public function updated($name)
    {
        if (in_array($name, ['search', 'type', 'month'])) {
            $this->resetPage();
        }
    }

    /**
     * Merender tampilan komponen aktivitas.
     * Mengambil data dari ActivityService yang mengelola logika penggabungan
     * data dari berbagai model (Union).
     */
    public function render(ActivityService $service)
    {
        return view('livewire.dashboard.recent-activity', [
            // Memanggil service untuk memproses query aktivitas dengan filter
            'activities' => $service->getActivities(
                $this->search,
                $this->type,
                $this->month,
                $this->perPage
            ),
            // Mengecek apakah pengguna saat ini adalah admin untuk kontrol akses/tampilan
            'isAdmin'    => Auth::user()->role === 'admin',
        ]);
    }
}
