<?php

namespace App\Livewire\Layout;

use Livewire\Component;

/**
 * Komponen Livewire untuk sidebar aplikasi.
 * Bertanggung jawab menampilkan menu navigasi sisi samping.
 */
class Sidebar extends Component
{
    /**
     * Merender view sidebar.
     */
    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
