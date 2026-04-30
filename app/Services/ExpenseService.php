<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseService
{
    public function getList(
        ?string $search = null,
        ?string $status = null,
        ?int $userId = null,
        ?int $month = null,
        ?int $year = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Expense::with('user')->latest();

        if (Auth::user()->role === 'user') {
            $query->where('user_id', Auth::id());
        } else {
            $query->when($userId, fn($q) => $q->where('user_id', $userId));
        }

        $query->when($status, fn($q) => $q->where('status', $status))
            ->when($month,  fn($q) => $q->whereMonth('date', $month))
            ->when($year,   fn($q) => $q->whereYear('date', $year))
            ->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"));

        return $query->paginate($perPage);
    }

    public function findOrFail(int $id): Expense
    {
        return Expense::with('user')->findOrFail($id);
    }

    public function findByUuidOrFail(string $uuid): Expense
    {
        return Expense::with('user')->where('uuid_expenses', $uuid)->firstOrFail();
    }

    public function store(array $data, ?UploadedFile $evidence = null): Expense
    {
        $path = $evidence?->store('evidence', 'public');

        $expense = Expense::create([
            'user_id'       => Auth::id(),
            'amount'        => $data['amount'],
            'description'   => $data['description'],
            'date'          => $data['date'],
            'evidence_path' => $path,
            'status'        => 'pending',
        ]);

        $this->notifyAdmins($expense);

        return $expense;
    }

    public function update(Expense $expense, array $data, ?UploadedFile $evidence = null): Expense
    {
        $updateData = [
            'amount'      => $data['amount'],
            'description' => $data['description'],
            'date'        => $data['date'],
        ];

        if ($evidence) {
            if ($expense->evidence_path) {
                Storage::disk('public')->delete($expense->evidence_path);
            }
            $updateData['evidence_path'] = $evidence->store('evidence', 'public');
        }

        $expense->update($updateData);
        return $expense->fresh();
    }

    public function approve(Expense $expense): void
    {
        $expense->update(['status' => 'approved']);
        $this->notifyUser($expense, 'approved');
    }

    public function reject(Expense $expense): void
    {
        $expense->update(['status' => 'rejected']);
        $this->notifyUser($expense, 'rejected');
    }

    public function delete(Expense $expense): void
    {
        if ($expense->evidence_path) {
            Storage::disk('public')->delete($expense->evidence_path);
        }
        $expense->delete();
    }

    // ── Private helpers (Disinkronkan dengan NotificationService) ──

    private function notifyAdmins(Expense $expense): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'         => $admin->id,
                'notifiable_id'   => $expense->id,
                'notifiable_type' => Expense::class,
                'data' => [
                    'icon'  => 'bi-arrow-up-circle',
                    'color' => 'text-danger',
                    'title' => 'Pengeluaran Baru',
                    'body'  => $expense->user->name . ': Rp ' . number_format($expense->amount, 0, ',', '.'),
                    'url'   => route('expenses.index'),
                ],
            ]);
        }
    }

    private function notifyUser(Expense $expense, string $status): void
    {
        $isApproved = $status === 'approved';

        Notification::create([
            'user_id'         => $expense->user_id,
            'notifiable_id'   => $expense->id,
            'notifiable_type' => Expense::class,
            'data' => [
                'icon'  => $isApproved ? 'bi-check-circle' : 'bi-x-circle',
                'color' => $isApproved ? 'text-success' : 'text-danger',
                'title' => 'Pengeluaran ' . ($isApproved ? 'Disetujui' : 'Ditolak'),
                'body'  => 'Status pengeluaran Rp ' . number_format($expense->amount, 0, ',', '.') . ' Anda telah diperbarui.',
                'url'   => route('expenses.index'), 
            ],
        ]);
    }
}
