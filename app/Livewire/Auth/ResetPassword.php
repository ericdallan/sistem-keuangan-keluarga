<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layout.guest')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // ── Mount ─────────────────────────────────────────────────────
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    // ── Reset Password ────────────────────────────────────────────
    public function resetPassword(): void
    {
        $this->validate([
            'token'    => ['required'],
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password'       => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', 'Hmm, sepertinya ada masalah dengan link reset ini. Coba minta link baru ya!');
            return;
        }

        Session::flash('status', 'Password berhasil diubah! Silakan masuk dengan password baru kamu.');
        $this->redirectRoute('login', navigate: true);
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
