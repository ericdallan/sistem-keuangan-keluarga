<div>

    {{-- ── Toast ── --}}
    @if (session()->has('success'))
        <div class="d-flex align-items-center gap-3 mb-4 p-3"
            style="background:#fff;border-radius:.75rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid var(--sk-primary)">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                style="width:32px;height:32px;background:var(--sk-primary-light);color:var(--sk-primary);font-size:15px">
                <i class="bi bi-check-lg"></i>
            </div>
            <span class="fw-semibold" style="font-size:13.5px;color:#0f5132">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="d-flex align-items-center gap-3 mb-4 p-3"
            style="background:#fff;border-radius:.75rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid #dc3545">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                style="width:32px;height:32px;background:#f8d7da;color:#dc3545;font-size:15px">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <span class="fw-semibold" style="font-size:13.5px;color:#842029">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ── Search + Tambah User ── --}}
    <div class="d-flex align-items-center gap-3 mb-4 px-3"
        style="background:#fff;border-radius:.75rem;box-shadow:0 2px 8px rgba(0,0,0,.05);height:50px">
        <i class="bi bi-search text-muted" style="flex-shrink:0"></i>
        <input wire:model.live.debounce.300ms="search" type="text" class="border-0 shadow-none form-control"
            style="background:transparent;font-size:14px" placeholder="Cari nama atau email pengguna...">
        <div style="width:1px;height:24px;background:rgba(0,0,0,.1);flex-shrink:0"></div>
        <button wire:click="openCreateModal"
            class="btn btn-success rounded-pill d-flex align-items-center gap-2 flex-shrink-0"
            style="background:var(--sk-primary-gradient);border:none;font-size:13px;font-weight:700;padding:7px 16px;white-space:nowrap">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </button>
    </div>

    {{-- ── Table Card ── --}}
    <div style="background:#fff;border-radius:.75rem;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background:#f8f9fa">
                    <tr>
                        <th class="ps-4 py-3"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                            Pengguna</th>
                        <th class="py-3"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                            Role</th>
                        <th class="py-3 d-none d-md-table-cell"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                            Posisi</th>
                        <th class="pe-4 py-3 text-end"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em;border:none">
                            Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $badgeStyle = match ($user->role) {
                                'admin' => 'background:#f8d7da;color:#842029',
                                default => 'background:var(--sk-primary-light);color:var(--sk-primary)',
                            };
                        @endphp
                        <tr style="border-bottom:1px solid rgba(0,0,0,.04)"
                            onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sk-avatar me-1">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="fw-bold" style="font-size:13.5px;color:#2d3436">{{ $user->name }}
                                        </div>
                                        <div style="font-size:11.5px;color:#adb5bd">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="rounded-pill px-3 py-1 fw-bold text-uppercase"
                                    style="font-size:.65rem;letter-spacing:.04em;{{ $badgeStyle }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:13px;color:#6c757d">
                                {{ $user->position ?? '-' }}
                            </td>
                            <td class="pe-4 text-end">
                                <button wire:click="edit({{ $user->id }})" class="sk-icon-btn me-1">
                                    <i class="bi bi-pencil-square" style="color:var(--sk-primary)"></i>
                                </button>
                                <button wire:click="delete({{ $user->id }})"
                                    wire:confirm="Data ini akan hilang selamanya. Lanjutkan?"
                                    onclick="return confirm('Data ini akan hilang selamanya. Lanjutkan?')"
                                    class="sk-icon-btn">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color:#adb5bd">
                                <i class="bi bi-people d-block mb-2" style="font-size:2rem"></i>
                                Data pengguna tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3" style="border-top:1px solid rgba(0,0,0,.06)">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ── Include Modal Partial ── --}}
    @include('livewire.users.partials.modal')

</div>
