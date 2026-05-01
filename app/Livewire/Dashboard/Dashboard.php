<?php

namespace App\Livewire\Dashboard;

use App\Services\DashboardService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Komponen Utama Dashboard.
 * Menampilkan ringkasan data keuangan dan grafik bulanan untuk user.
 */
#[Layout('livewire.layout.app')]
#[Title('Dashboard Pengguna')]
class Dashboard extends Component
{

    /**
     * Menginisialisasi dashboard dan menampilkan toast notifikasi
     * jika ada pesan flash dari proses login sebelumnya.
     */
    public function mount(): void
    {
        // Kirim toast jika ada session flash dari login
        if (session()->has('toast_message')) {
            $this->dispatch(
                'toast',
                message: session('toast_message'),
                type: session('toast_type', 'success')
            );
        }
    }

    /**
     * Merender halaman dashboard utama.
     * Mengambil data summary dan statistik grafik melalui DashboardService.
     */
    public function render(DashboardService $service)
    {
        $summary = $service->getSummary();
        $chartData = $service->getMonthlyChart();

        return view('livewire.dashboard.index', [
            'summary'   => $summary,
            'chartData' => $chartData,
        ])->layout('livewire.layout.app');
    }
}
