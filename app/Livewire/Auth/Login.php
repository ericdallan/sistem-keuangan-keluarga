<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layout.guest')]
#[Title('Masuk')]
class Login extends Component
{
    public LoginForm $form;

    // ── Authenticate ─────────────────────────────────────────────
    public function login(): void
    {
        try {
            $this->validate();
            $this->form->authenticate();
            Session::regenerate();

            $this->dispatch('toast', [
                'message' => 'Selamat datang kembali! Senang melihat Anda di sistem.',
                'type' => 'success'
            ]);

            $this->redirect($this->form->getRedirectRoute(), navigate: true);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'form.email' => 'Hmm, email atau password yang kamu masukkan kayaknya belum cocok. Coba dicek lagi ya!',
            ]);
        }
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.auth.login');
    }
}
