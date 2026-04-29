<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', 'Oops, kami nggak bisa menemukan akun dengan email tersebut. Coba cek lagi ya atau daftar dulu kalau belum punya akun!');
            return;
        }

        $this->reset('email');

        session()->flash('status', 'Yeay! Link reset password sudah dikirim ke email kamu. Cek inbox (atau folder spam) ya!');
    }
}; ?>

<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Lupa Password?</h5>
        <p class="small mb-0 opacity-75">Tenang, kami bantu pulihkan</p>
    </div>

    <div class="card-body p-4">
        <div class="mb-4 text-muted small">
            Masukkan email kamu di bawah ini, nanti kami akan kirimkan link buat reset password baru. Gampang kan?
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink">
            <!-- Email Address -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <x-text-input wire:model="email" id="email" class="form-control border-0 py-2" type="email"
                        name="email" placeholder="nama@email.com" required autofocus />
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-danger mt-2 small fw-medium" />
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        Kirim Link Reset <i class="bi bi-send ms-1"></i>
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Mengirim...
                    </span>
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" wire:navigate
                    class="text-muted small text-decoration-none fw-semibold hover-sk-primary">
                    <i class="bi bi-arrow-left me-1"></i> Ingat password? Masuk di sini
                </a>
            </div>
        </form>
    </div>
</div>
