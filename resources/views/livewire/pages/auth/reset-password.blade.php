<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('livewire.layout.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', 'Hmm, sepertinya ada masalah dengan link reset ini. Coba minta link baru ya!');
            return;
        }

        Session::flash('status', 'Password berhasil diubah! Silakan masuk dengan password baru kamu.');

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Reset Password</h5>
        <p class="small mb-0 opacity-75">Buat password baru yang kuat ya</p>
    </div>

    <div class="card-body p-4">
        <form wire:submit="resetPassword">
            <!-- Email Address -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <x-text-input wire:model="email" id="email" class="form-control border-0 py-2" type="email"
                        name="email" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-danger mt-2 small fw-medium" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Password Baru</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-key text-muted"></i></span>
                    <x-text-input wire:model="password" id="password" class="form-control border-0 py-2"
                        type="password" name="password" placeholder="Minimal 8 karakter" required
                        autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-danger mt-2 small fw-medium" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Konfirmasi Password</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-key-fill text-muted"></i></span>
                    <x-text-input wire:model="password_confirmation" id="password_confirmation"
                        class="form-control border-0 py-2" type="password" name="password_confirmation"
                        placeholder="Ulangi password baru" required autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2 small fw-medium" />
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        Simpan Password Baru <i class="bi bi-check-circle ms-1"></i>
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" wire:navigate
                    class="text-muted small text-decoration-none fw-semibold hover-sk-primary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>
