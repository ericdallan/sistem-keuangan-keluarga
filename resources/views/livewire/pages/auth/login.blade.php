<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        try {
            $this->validate();
            $this->form->authenticate();
            Session::regenerate();
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'form.email' => 'Hmm, email atau password yang kamu masukkan kayaknya belum cocok nih. Coba dicek lagi ya!',
            ]);
        }
    }
}; ?>

<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Selamat Datang Kembali</h5>
        <p class="small mb-0 opacity-75">Silakan masuk untuk melanjutkan</p>
    </div>

    <div class="card-body p-4">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            <!-- Email Address -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <x-text-input wire:model="form.email" id="email" class="form-control border-0 py-2"
                        type="email" name="email" placeholder="nama@email.com" required autofocus
                        autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('form.email')" class="text-danger mt-2 small fw-medium" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a class="small text-decoration-none fw-bold text-sk-primary"
                            href="{{ route('password.request') }}" wire:navigate>
                            Lupa?
                        </a>
                    @endif
                </div>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm" id="pwd_group">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-key text-muted"></i></span>
                    <x-text-input wire:model="form.password" id="password" class="form-control border-0 py-2"
                        type="password" name="password" placeholder="••••••••" required
                        autocomplete="current-password" />
                    <button class="btn bg-white border-0 text-muted px-3" type="button" onclick="togglePassword()">
                        <i class="bi bi-eye-slash" id="toggle_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="text-danger mt-2 small fw-medium" />
            </div>

            <!-- Remember Me -->
            <div class="mb-4 form-check">
                <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                    name="remember">
                <label for="remember" class="form-check-label small text-muted">Tetap masuk di perangkat ini</label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        Masuk Sekarang <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Memproses...
                    </span>
                </button>
            </div>

            <div class="text-center">
                <a href="/" wire:navigate
                    class="text-muted small text-decoration-none fw-semibold hover-sk-primary">
                    <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggle_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>
