<div class="card border-0 rounded-4 overflow-hidden">
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
        <h5 class="fw-bold mb-1">Verifikasi Email</h5>
        <p class="small mb-0 opacity-75">Satu langkah lagi!</p>
    </div>

    <div class="card-body p-4">
        <div class="mb-4 text-muted small">
            Terima kasih sudah mendaftar! Sebelum mulai, tolong verifikasi email kamu dengan klik link
            yang sudah kami kirimkan. Kalau belum menerima emailnya, kami bisa kirim ulang.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small fw-semibold mb-4">
                Link verifikasi baru sudah dikirim ke email kamu!
            </div>
        @endif

        <div class="d-grid mb-3">
            <button wire:click="sendVerification"
                class="btn-sk-primary btn-lg rounded-pill fw-bold py-2 shadow-sm border-0 w-100"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Ulang Email Verifikasi <i class="bi bi-envelope ms-1"></i></span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...
                </span>
            </button>
        </div>

        <div class="text-center">
            <button wire:click="logout" type="button"
                class="btn btn-link text-muted small text-decoration-none fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar
            </button>
        </div>
    </div>
</div>
