<?php

namespace App\Livewire;

use App\Services\DashboardService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layout.app')]
#[Title('Dashboard Pengguna')]
class Dashboard extends Component
{
    public function render(DashboardService $service)
    {
        $summary = $service->getSummary();
        $chartData = $service->getMonthlyChart();

        return view('livewire.dashboard', [
            'summary' => $summary,
            'chartData' => $chartData,
        ])->layout('livewire.layout.app');
    }
}
