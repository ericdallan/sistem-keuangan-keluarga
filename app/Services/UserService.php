<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * List semua user (admin only), kecuali diri sendiri.
     */
    public function getAll(
        ?string $search = null,
        ?string $role = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return User::query()
            ->where('id', '!=', Auth::id()) // Kecuali diri sendiri (sesuai deskripsi function kamu)
            ->when($search, function ($q) use ($search) {
                // Gunakan grouping where agar filter search tidak mengacaukan filter role/id
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, fn($q) => $q->where('role', $role))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find single user by ID atau UUID.
     */
    public function findOrFail($id): User
    {
        // Mendukung pencarian via ID internal atau uuid_users (untuk Route Model Binding)
        if (is_numeric($id)) {
            return User::findOrFail($id);
        }

        return User::where('uuid_users', $id)->firstOrFail();
    }

    /**
     * Buat user baru.
     */
    public function store(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'position' => $data['position'],
            // uuid_users otomatis terisi oleh trait HasUuids di Model
        ]);
    }

    /**
     * Update data user.
     */
    public function update(User $user, array $data): User
    {
        $payload = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'position' => $data['position'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user->fresh();
    }

    /**
     * Hapus user.
     */
    public function delete(User $user): void
    {
        // Pastikan tidak menghapus diri sendiri melalui service
        if ($user->id === Auth::id()) {
            throw new \Exception("Anda tidak dapat menghapus akun Anda sendiri.");
        }

        $user->delete();
    }

    /**
     * List user dengan role 'user' saja.
     */
    public function getUsersOnly(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', 'user')->orderBy('name')->get();
    }
}
