<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layout.guest')]
#[Title('Lupa Password')]
class ForgotPassword extends Component
{
    public string $email = '';

    // ── Send Reset Link ───────────────────────────────────────────
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', 'Oops, kami nggak bisa menemukan akun dengan email tersebut. Coba cek lagi ya!');
            return;
        }

        $this->reset('email');
        session()->flash('status', 'Yeay! Link reset password sudah dikirim ke email kamu. Cek inbox (atau folder spam) ya!');
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
