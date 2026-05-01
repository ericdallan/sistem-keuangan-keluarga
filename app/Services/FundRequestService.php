<?php

namespace App\Services;

use App\Models\FundRequest;
use App\Models\Income;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Service untuk mengelola pengajuan dana (Fund Request).
 * Menangani pengajuan oleh user dan proses approval oleh admin.
 */
class FundRequestService
{
    /**
     * Mengambil daftar pengajuan dana dengan filter pencarian dan otorisasi.
     * User hanya melihat pengajuannya sendiri, Admin melihat seluruh pengajuan.
     */
    public function getList(
        ?string $search = null,
        ?string $status = null,
        ?int    $userId = null,
        ?string $month  = null,
        int     $perPage = 10
    ): LengthAwarePaginator {
        $query = FundRequest::with('user')->latest();

        if (Auth::user()->role === 'user') {
            $query->where('user_id', Auth::id());
        } else {
            $query->when($userId, fn($q) => $q->where('user_id', $userId));
        }

        $query->when($search, fn($q) => $q->where('reason', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($month,  fn($q) => $q->where('month', $month));

        return $query->paginate($perPage);
    }

    /**
     * Mencari pengajuan dana berdasarkan ID (numeric) atau UUID.
     */
    public function findOrFail($id): FundRequest
    {
        if (is_numeric($id)) {
            return FundRequest::with('user')->findOrFail($id);
        }

        return FundRequest::with('user')->where('uuid_fund_requests', $id)->firstOrFail();
    }

    /**
     * Menyimpan pengajuan dana baru yang dibuat oleh user.
     */
    public function store(array $data): FundRequest
    {
        $fundRequest = FundRequest::create([
            'user_id' => Auth::id(),
            'amount'  => $data['amount'],
            'reason'  => $data['reason'],
            'date'    => now()->toDateString(),
            'month'   => $data['month'],
            'status'  => 'pending',
        ]);

        $this->notifyAdmins($fundRequest);

        return $fundRequest;
    }

    /**
     * Memperbarui data pengajuan dana (hanya berlaku jika status masih pending).
     */
    public function update(FundRequest $fundRequest, array $data): FundRequest
    {
        $fundRequest->update([
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'month'  => $data['month'],
        ]);

        return $fundRequest->fresh();
    }

    /**
     * Menyetujui pengajuan dana dan otomatis menambahkannya ke Master Pemasukan (Income).
     * Menggunakan Database Transaction untuk menjamin integritas data.
     */
    public function approve(FundRequest $fundRequest): void
    {
        DB::transaction(function () use ($fundRequest) {
            // Ubah status pengajuan menjadi approved
            $fundRequest->update(['status' => 'approved']);

            // Konversi bulan Y-m menjadi tanggal awal bulan untuk pencatatan Income
            $incomeDate = Carbon::createFromFormat('Y-m', $fundRequest->month)
                ->startOfMonth()
                ->toDateString();

            // Masukkan nominal ke tabel pemasukan (Income) secara otomatis
            Income::create([
                'uuid_incomes' => (string) Str::uuid(),
                'user_id'      => $fundRequest->user_id,
                'amount'       => $fundRequest->amount,
                'description'  => 'Pencairan Dana: ' . $fundRequest->reason,
                'date'         => $incomeDate,
                'category'     => 'fund_request',
            ]);
        });

        $this->notifyUser($fundRequest, 'approved');
    }

    /**
     * Menolak pengajuan dana dan mengirim notifikasi ke user.
     */
    public function reject(FundRequest $fundRequest, ?string $reason = null): void
    {
        $fundRequest->update(['status' => 'rejected']);
        $this->notifyUser($fundRequest, 'rejected');
    }

    /**
     * Menghapus data pengajuan dana.
     */
    public function delete(FundRequest $fundRequest): void
    {
        $fundRequest->delete();
    }

    /**
     * Menghitung jumlah pengajuan yang masih dalam status pending.
     * Berguna untuk badge notifikasi di dashboard Admin.
     */
    public function countPending(): int
    {
        return FundRequest::where('status', 'pending')->count();
    }

    // ── Helper Notifikasi ───────────────────────────────────────────

    private function notifyAdmins(FundRequest $fundRequest): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'         => $admin->id,
                'notifiable_id'   => $fundRequest->id,
                'notifiable_type' => FundRequest::class,
                'data' => [
                    'icon'  => 'bi-cash-coin',
                    'color' => 'text-primary',
                    'title' => 'Pengajuan Dana Baru',
                    'body'  => $fundRequest->user->name . ': Rp ' . number_format($fundRequest->amount, 0, ',', '.'),
                    'url'   => route('fund-requests.index'),
                ],
            ]);
        }
    }

    private function notifyUser(FundRequest $fundRequest, string $status): void
    {
        $isApproved = $status === 'approved';

        Notification::create([
            'user_id'         => $fundRequest->user_id,
            'notifiable_id'   => $fundRequest->id,
            'notifiable_type' => FundRequest::class,
            'data' => [
                'icon'  => $isApproved ? 'bi-check-circle' : 'bi-x-circle',
                'color' => $isApproved ? 'text-success' : 'text-danger',
                'title' => 'Pengajuan Dana ' . ($isApproved ? 'Disetujui' : 'Ditolak'),
                'body'  => 'Pengajuan dana Rp ' . number_format($fundRequest->amount, 0, ',', '.') . ' Anda telah ' . ($isApproved ? 'disetujui.' : 'ditolak.'),
                'url'   => route('fund-requests.index'),
            ],
        ]);
    }
}
