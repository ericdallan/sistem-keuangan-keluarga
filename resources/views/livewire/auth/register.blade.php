<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Buat Akun Baru</h5>
        <p class="small mb-0 opacity-75">Daftar untuk mulai menggunakan aplikasi</p>
    </div>

    <div class="card-body p-4">
        <form wire:submit="register">
            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-person text-muted"></i></span>
                    <input wire:model="name" id="name" type="text" name="name"
                        class="form-control border-0 py-2 @error('name') is-invalid @enderror"
                        placeholder="Nama lengkap kamu" required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input wire:model="email" id="email" type="email" name="email"
                        class="form-control border-0 py-2 @error('email') is-invalid @enderror"
                        placeholder="nama@email.com" required autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-key text-muted"></i></span>
                    <input wire:model="password" id="password" type="password" name="password"
                        class="form-control border-0 py-2 @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter" required autocomplete="new-password">
                </div>
                @error('password')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Konfirmasi Password</label>
                <div class="input-group border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-key-fill text-muted"></i></span>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password"
                        name="password_confirmation"
                        class="form-control border-0 py-2 @error('password_confirmation') is-invalid @enderror"
                        placeholder="Ulangi password kamu" required autocomplete="new-password">
                </div>
                @error('password_confirmation')
                    <div class="text-danger mt-2 small fw-medium">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Daftar Sekarang <i class="bi bi-person-plus ms-1"></i></span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Memproses...
                    </span>
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" wire:navigate class="text-muted small text-decoration-none fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Sudah punya akun? Masuk di sini
                </a>
            </div>
        </form>
    </div>
</div>
