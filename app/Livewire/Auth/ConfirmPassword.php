<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layout.guest')]
#[Title('Konfirmasi Password')]
class ConfirmPassword extends Component
{
    public string $password = '';

    // ── Confirm ───────────────────────────────────────────────────
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email'    => Auth::user()->email,
            'password' => $this->password,
        ])) {
            $this->addError('password', 'Password yang kamu masukkan tidak sesuai.');
            return;
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.auth.confirm-password');
    }
}