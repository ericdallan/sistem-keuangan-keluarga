<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Konfirmasi Password</h5>
        <p class="small mb-0 opacity-75">Pastikan ini memang kamu</p>
    </div>

    <div class="card-body p-4">
        <div class="mb-4 text-muted small">
            Ini adalah area aman. Masukkan password kamu untuk melanjutkan.
        </div>

        <form wire:submit="confirmPassword">
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input wire:model="password" id="password" type="password" name="password"
                        class="form-control border-0 py-2 @error('password') is-invalid @enderror"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Konfirmasi <i class="bi bi-shield-check ms-1"></i></span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Memverifikasi...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
