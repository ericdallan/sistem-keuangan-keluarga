<?php

namespace App\Livewire\Welcome;

use Livewire\Component;

/**
 * Komponen Livewire untuk navigasi halaman depan (Welcome Page).
 * Menampilkan menu navigasi yang relevan bagi pengunjung/tamu.
 */
class Navigation extends Component
{
    /**
     * Merender tampilan navigasi halaman selamat datang.
     */
    public function render()
    {
        return view('livewire.welcome.navigation');
    }
}
