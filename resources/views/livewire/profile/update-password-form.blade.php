{{-- Card Header --}}
<div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-danger bg-opacity-10 text-danger"
        style="width:40px;height:40px;font-size:1.1rem">
        <i class="bi bi-shield-lock"></i>
    </div>
    <div>
        <h5 class="fw-bold mb-0" style="font-size:.95rem">Ubah Password</h5>
        <p class="mb-0 text-muted" style="font-size:.78rem">Gunakan password yang panjang dan acak agar akun tetap aman
        </p>
    </div>
</div>

<form wire:submit.prevent="updatePassword">
    <div class="row g-3">

        {{-- Password Saat Ini --}}
        <div class="col-12">
            <label class="form-label fw-semibold text-uppercase text-muted"
                style="font-size:.75rem;letter-spacing:.05em">
                Password Saat Ini
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" wire:model="current_password" id="current_password"
                    class="form-control border-0 bg-light @error('current_password') is-invalid @enderror"
                    placeholder="Masukkan password saat ini" autocomplete="current-password">
                <button type="button" class="input-group-text bg-light text-muted border-0"
                    onclick="togglePassword('current_password', 'toggle_current')">
                    <i class="bi bi-eye-slash" id="toggle_current"></i>
                </button>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Password Baru --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold text-uppercase text-muted"
                style="font-size:.75rem;letter-spacing:.05em">
                Password Baru
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-key"></i>
                </span>
                <input type="password" wire:model="password" id="password"
                    class="form-control border-0 bg-light @error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter" autocomplete="new-password">
                <button type="button" class="input-group-text bg-light text-muted border-0"
                    onclick="togglePassword('password', 'toggle_new')">
                    <i class="bi bi-eye-slash" id="toggle_new"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold text-uppercase text-muted"
                style="font-size:.75rem;letter-spacing:.05em">
                Konfirmasi Password
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-key-fill"></i>
                </span>
                <input type="password" wire:model="password_confirmation" id="password_confirmation"
                    class="form-control border-0 bg-light @error('password_confirmation') is-invalid @enderror"
                    placeholder="Ulangi password baru" autocomplete="new-password">
                <button type="button" class="input-group-text bg-light text-muted border-0"
                    onclick="togglePassword('password_confirmation', 'toggle_confirm')">
                    <i class="bi bi-eye-slash" id="toggle_confirm"></i>
                </button>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    {{-- Submit --}}
    <div class="mt-4 pt-3 d-flex justify-content-end border-top">
        <button type="submit" class="btn btn-danger rounded-pill d-flex align-items-center gap-2">
            <div wire:loading wire:target="updatePassword" class="spinner-border spinner-border-sm" role="status">
            </div>
            <i class="bi bi-shield-check" wire:loading.remove wire:target="updatePassword"></i>
            Perbarui Password
        </button>
    </div>
</form>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>
