<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
        <div class="modal-content p-4" style="border-radius:1rem;border:none;box-shadow:0 20px 50px rgba(0,0,0,.15)">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                    style="width:64px;height:64px;background:#f8d7da;color:#dc3545;font-size:1.75rem">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 class="fw-bold mb-1">Hapus Pengguna?</h5>
                <p class="text-muted mb-0" style="font-size:.875rem">
                    "<span class="fw-semibold text-dark">{{ $deleteUserName }}</span>" akan dihapus secara permanen.
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" data-bs-dismiss="modal" class="btn btn-light rounded-pill px-4 fw-semibold"
                    style="font-size:.875rem">
                    Batal
                </button>
                <button wire:click="executeDelete" class="btn btn-danger rounded-pill px-4 fw-semibold"
                    style="font-size:.875rem">
                    <i class="bi bi-trash3 me-1"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
