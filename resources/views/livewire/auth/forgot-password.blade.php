<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Lupa Password?</h5>
        <p class="small mb-0 opacity-75">Tenang, kami bantu pulihkan</p>
    </div>

    <div class="card-body p-4">
        <div class="mb-4 text-muted small">
            Masukkan email kamu di bawah ini, nanti kami akan kirimkan link buat reset password baru. Gampang kan?
        </div>

        @if (session('status'))
            <div class="alert alert-success small fw-semibold mb-4">{{ session('status') }}</div>
        @endif

        <form wire:submit="sendPasswordResetLink">
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input wire:model="email" id="email" type="email" name="email"
                        class="form-control border-0 py-2 @error('email') is-invalid @enderror"
                        placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Kirim Link Reset <i class="bi bi-send ms-1"></i></span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...
                    </span>
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" wire:navigate class="text-muted small text-decoration-none fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Ingat password? Masuk di sini
                </a>
            </div>
        </form>
    </div>
</div>
