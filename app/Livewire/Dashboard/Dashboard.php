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
