<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Service untuk manajemen data pengguna (User Management).
 * Digunakan terutama oleh Admin untuk CRUD data user.
 */
class UserService
{
    /**
     * Mengambil daftar user dengan fitur pencarian dan filter role.
     */
    public function getAll(
        ?string $search = null,
        ?string $role = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return User::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, fn($q) => $q->where('role', $role))
            // Mengurutkan berdasarkan posisi keluarga jika diperlukan
            ->orderByRaw("FIELD(position, 'husband', 'wife', 'child')")
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mencari user berdasarkan ID atau UUID.
     */
    public function findOrFail($id): User
    {
        if (is_numeric($id)) {
            return User::findOrFail($id);
        }
        return User::where('uuid_users', $id)->firstOrFail();
    }

    /**
     * Membuat akun user baru oleh Admin.
     */
    public function store(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'position' => $data['position'],
        ]);
    }

    /**
     * Memperbarui data user.
     */
    public function update(User $user, array $data): User
    {
        $payload = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'position' => $data['position'],
        ];

        // Update password hanya jika diisi
        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user->fresh();
    }

    /**
     * Menghapus akun user dengan validasi keamanan.
     */
    public function delete(User $user): void
    {
        if ($user->id === Auth::id()) {
            throw new \Exception("Anda tidak dapat menghapus akun Anda sendiri.");
        }
        $user->delete();
    }

    /**
     * Mengambil koleksi user yang memiliki role 'user'.
     */
    public function getUsersOnly(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', 'user')->orderBy('name')->get();
    }
}
