<?php

namespace App\Livewire\Dashboard;

use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Komponen Livewire untuk menampilkan aktivitas terbaru.
 * Menangani filter pencarian, tipe aktivitas, dan bulan untuk dashboard.
 */
class RecentActivity extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $type    = '';
    public string $month   = '';
    public int    $perPage = 10;

    /**
     * Hook Livewire: Reset ke halaman pertama saat properti filter berubah.
     */
    public function updated($name)
    {
        if (in_array($name, ['search', 'type', 'month'])) {
            $this->resetPage();
        }
    }

    /**
     * Merender tampilan dashboard aktivitas.
     * Menggunakan ActivityService untuk mengambil data yang telah di-union.
     */
    public function render(ActivityService $service)
    {
        return view('livewire.dashboard.recent-activity', [
            'activities' => $service->getActivities(
                $this->search,
                $this->type,
                $this->month,
                $this->perPage
            ),
            'isAdmin'    => Auth::user()->role === 'admin',
        ]);
    }
}
