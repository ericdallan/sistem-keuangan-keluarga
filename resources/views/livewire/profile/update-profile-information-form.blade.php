{{-- Card Header --}}
<div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-sk-primary-subtle text-sk-primary"
        style="width:40px;height:40px;font-size:1.1rem">
        <i class="bi bi-person"></i>
    </div>
    <div>
        <h5 class="fw-bold mb-0" style="font-size:.95rem">Informasi Profil</h5>
        <p class="mb-0 text-muted" style="font-size:.78rem">Perbarui nama dan alamat email akun kamu</p>
    </div>
</div>

<form wire:submit.prevent="updateProfile">
    <div class="row g-3">

        {{-- Nama --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold text-uppercase text-muted"
                style="font-size:.75rem;letter-spacing:.05em">
                Nama Lengkap
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" wire:model="name"
                    class="form-control border-0 bg-light @error('name') is-invalid @enderror"
                    placeholder="Nama lengkap">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold text-uppercase text-muted"
                style="font-size:.75rem;letter-spacing:.05em">
                Alamat Email
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" wire:model="email"
                    class="form-control border-0 bg-light @error('email') is-invalid @enderror"
                    placeholder="email@contoh.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Email belum terverifikasi --}}
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div class="col-12">
                <div
                    class="d-flex align-items-center gap-2 p-3 rounded-3 border-start border-4 border-warning bg-warning bg-opacity-10">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                    <span class="small text-warning-emphasis">
                        Email belum terverifikasi.
                        <button type="button" wire:click="resendVerification"
                            class="btn btn-link p-0 fw-semibold small text-sk-primary">
                            Kirim ulang link verifikasi
                        </button>
                    </span>
                </div>
            </div>
        @endif

    </div>

    {{-- Submit --}}
    <div class="mt-4 pt-3 d-flex justify-content-end border-top">
        <button type="submit" class="btn btn-sk-primary rounded-pill d-flex align-items-center gap-2">
            <div wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm" role="status"></div>
            <i class="bi bi-check-lg" wire:loading.remove wire:target="updateProfile"></i>
            Simpan Perubahan
        </button>
    </div>
</form>
