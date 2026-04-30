<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layout.app')]
#[Title('Kelola Pengguna')]
class Index extends Component
{
    use WithPagination;

    // ── State ────────────────────────────────────────────────────
    public bool $isModalOpen    = false;
    public bool $isEditMode     = false;
    public ?string $modalError  = null;

    // ── Form Fields ──────────────────────────────────────────────
    public $userId, $name, $email, $password;
    public $role     = '';
    public $position = '';

    // ── Search ───────────────────────────────────────────────────
    public $search = '';

    // ── Delete Confirmation ──────────────────────────────────────
    public bool $confirmDeleteModal = false;
    public ?int $deleteUserId       = null;
    public ?string $deleteUserName  = null;

    // ── Pagination ───────────────────────────────────────────────
    protected $paginationTheme = 'bootstrap';

    // ── Modal: Create ────────────────────────────────────────────
    public function openCreateModal(): void
    {
        $this->reset(['name', 'email', 'password', 'role', 'position', 'userId', 'isEditMode', 'modalError']);
        $this->isModalOpen = true;
        $this->dispatch('open-modal', modal: 'userModal');
    }

    // ── Modal: Close ─────────────────────────────────────────────
    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetErrorBag();
        $this->dispatch('close-modal', modal: 'userModal');
    }

    // ── Form: Reset ──────────────────────────────────────────────
    public function resetForm(): void
    {
        $this->reset(['name', 'email', 'password', 'role', 'position', 'userId', 'isEditMode', 'modalError']);
        $this->resetErrorBag();
    }

    // ── CRUD: Save (Create / Update) ─────────────────────────────
    public function save(UserService $userService): void
    {
        $this->modalError = null;

        $validated = $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->userId,
            'role'     => 'required|in:admin,user',
            'position' => 'required|in:husband,wife,child',
            'password' => $this->isEditMode ? 'nullable|min:8' : 'required|min:8',
        ]);

        // Business rule: hanya boleh satu admin
        if (!$this->isEditMode) {
            if ($this->role === 'admin' && User::where('role', 'admin')->exists()) {
                $this->modalError = 'Cukup satu Imam di saf depan. Kalau dua, nanti makmumnya bingung mau amin-kan yang mana.';
                return;
            }

            // Business rule: hanya boleh satu istri
            if ($this->position === 'wife' && User::where('position', 'wife')->count() >= 1) {
                $this->modalError = 'Satu istri adalah sunnah, dua istri adalah fitnah bagi database yang RAM-nya cuma 8GB.';
                return;
            }
        }

        if ($this->isEditMode) {
            $user = $userService->findOrFail($this->userId);
            $userService->update($user, $validated);
            $this->dispatch('toast', message: 'Data pengguna berhasil diperbarui.');
        } else {
            $userService->store($validated);
            $this->dispatch('toast', message: 'User baru berhasil didaftarkan.');
        }

        $this->dispatch('close-modal', modal: 'userModal');
        $this->reset(['name', 'email', 'password', 'role', 'position', 'userId', 'isEditMode', 'modalError']);
    }

    // ── CRUD: Edit ───────────────────────────────────────────────
    public function edit(int $id, UserService $userService): void
    {
        $user = $userService->findOrFail($id);

        $this->userId     = $user->id;
        $this->name       = $user->name;
        $this->email      = $user->email;
        $this->role       = $user->role;
        $this->position   = $user->position;
        $this->isEditMode = true;
        $this->modalError = null;
        $this->isModalOpen = true;

        $this->dispatch('open-modal', modal: 'userModal');
    }

    // ── CRUD: Confirm Delete ─────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        $user = User::findOrFail($id);

        $this->deleteUserId   = $user->id;
        $this->deleteUserName = $user->name;
        $this->confirmDeleteModal = true;

        $this->dispatch('open-modal', modal: 'deleteModal');
    }

    // ── CRUD: Execute Delete ─────────────────────────────────────
    public function executeDelete(UserService $userService): void
    {
        try {
            $user = $userService->findOrFail($this->deleteUserId);
            $userService->delete($user);
            $this->dispatch('toast', message: 'User "' . $this->deleteUserName . '" telah dihapus dari peradaban.');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

        $this->confirmDeleteModal = false;
        $this->deleteUserId       = null;
        $this->deleteUserName     = null;

        $this->dispatch('close-modal', modal: 'deleteModal');
    }

    // ── Lifecycle: Role Changed ──────────────────────────────────
    // Auto-set posisi ketika role berubah:
    // admin → husband, user → kosongkan pilihan posisi
    public function updatedRole(string $value): void
    {
        $this->position = $value === 'admin' ? 'husband' : '';
    }

    // ── Render ───────────────────────────────────────────────────
    public function render(UserService $userService)
    {
        return view('livewire.users.index', [
            'users' => $userService->getAll($this->search),
        ]);
    }
}
