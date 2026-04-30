<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
            style="border-radius:1rem;overflow:hidden;border:none;box-shadow:0 20px 50px rgba(0,0,0,.15)">

            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
                style="background:var(--sk-primary-gradient);border-radius:1rem 1rem 0 0">
                <h5 class="m-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size:15px">
                    <i class="bi {{ $isEditMode ? 'bi-pencil-square' : 'bi-person-plus-fill' }}"></i>
                    {{ $isEditMode ? 'Perbarui Pengguna' : 'Tambah Pengguna Baru' }}
                </h5>
                <button type="button" wire:click="closeModal" data-bs-dismiss="modal"
                    class="d-flex align-items-center justify-content-center border-0 text-white"
                    style="width:28px;height:28px;border-radius:.45rem;background:rgba(255,255,255,.2);cursor:pointer;font-size:18px;line-height:1">
                    &times;
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-4">

                    {{-- Error Message --}}
                    @if ($modalError)
                        <div class="d-flex align-items-center gap-2 mb-3 p-3 rounded-3"
                            style="background:#f8d7da;border:1px solid #f5c2c7;">
                            <i class="bi bi-exclamation-circle-fill text-danger" style="font-size:16px"></i>
                            <span class="fw-semibold" style="font-size:13px;color:#842029">{{ $modalError }}</span>
                        </div>
                    @endif

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="d-block mb-1"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                            Nama Lengkap
                        </label>
                        <div class="d-flex align-items-center"
                            style="background:#f8f9fa;border-radius:.55rem;border:1.5px solid transparent;overflow:hidden;transition:border .15s,background .15s"
                            onfocusin="this.style.borderColor='var(--sk-primary)';this.style.background='#fff'"
                            onfocusout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
                            <span class="px-3" style="color:#adb5bd;font-size:14px;flex-shrink:0">
                                <i class="bi bi-person"></i>
                            </span>
                            <input wire:model="name" type="text"
                                class="border-0 shadow-none form-control @error('name') is-invalid @enderror"
                                style="background:transparent;font-size:13.5px;padding:9px 10px 9px 0"
                                placeholder="Contoh: Eric Dallan">
                        </div>
                        @error('name')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="d-block mb-1"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                            Alamat Email
                        </label>
                        <div class="d-flex align-items-center"
                            style="background:#f8f9fa;border-radius:.55rem;border:1.5px solid transparent;overflow:hidden;transition:border .15s,background .15s"
                            onfocusin="this.style.borderColor='var(--sk-primary)';this.style.background='#fff'"
                            onfocusout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
                            <span class="px-3" style="color:#adb5bd;font-size:14px;flex-shrink:0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input wire:model="email" type="email"
                                class="border-0 shadow-none form-control @error('email') is-invalid @enderror"
                                style="background:transparent;font-size:13.5px;padding:9px 10px 9px 0"
                                placeholder="nama@email.com" autocomplete="username">
                        </div>
                        @error('email')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role & Posisi --}}
                    <div class="row g-3 mb-3">

                        {{-- Role --}}
                        <div class="col-12 col-sm-6">
                            <label class="d-block mb-1"
                                style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                Role
                            </label>
                            <div class="dropdown"
                                style="background:#f8f9fa;border-radius:.55rem;border:1.5px solid transparent;transition:border .15s,background .15s"
                                onfocusin="this.style.borderColor='var(--sk-primary)';this.style.background='#fff'"
                                onfocusout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
                                <button type="button" data-bs-toggle="dropdown"
                                    class="btn w-100 d-flex align-items-center gap-2 border-0 shadow-none @error('role') is-invalid @enderror"
                                    style="background:transparent;font-size:13.5px;padding:9px 12px;color:{{ $role ? '#2d3436' : '#adb5bd' }}">
                                    <i class="bi bi-shield" style="color:#adb5bd;font-size:14px;flex-shrink:0"></i>
                                    <span
                                        class="flex-grow-1 text-start">{{ $role ? ucfirst($role) : 'Pilih...' }}</span>
                                    <i class="bi bi-chevron-down"
                                        style="font-size:11px;color:#adb5bd;flex-shrink:0"></i>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm py-1"
                                    style="border-radius:.55rem;border:1px solid rgba(0,0,0,.08);font-size:13.5px">
                                    <li>
                                        <a class="dropdown-item py-2" href="#"
                                            wire:click.prevent="$set('role', 'admin')">
                                            Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="#"
                                            wire:click.prevent="$set('role', 'user')">
                                            User
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @error('role')
                                <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Posisi --}}
                        <div class="col-12 col-sm-6">
                            <label class="d-block mb-1"
                                style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                                Posisi
                            </label>
                            <div class="dropdown"
                                style="background:#f8f9fa;border-radius:.55rem;border:1.5px solid transparent;transition:border .15s,background .15s"
                                onfocusin="this.style.borderColor='var(--sk-primary)';this.style.background='#fff'"
                                onfocusout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
                                <button type="button" data-bs-toggle="dropdown"
                                    class="btn w-100 d-flex align-items-center gap-2 border-0 shadow-none @error('position') is-invalid @enderror"
                                    style="background:transparent;font-size:13.5px;padding:9px 12px;color:{{ $position ? '#2d3436' : '#adb5bd' }}">
                                    <i class="bi bi-people" style="color:#adb5bd;font-size:14px;flex-shrink:0"></i>
                                    <span
                                        class="flex-grow-1 text-start">{{ $position ? ucfirst($position) : 'Pilih...' }}</span>
                                    <i class="bi bi-chevron-down"
                                        style="font-size:11px;color:#adb5bd;flex-shrink:0"></i>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm py-1"
                                    style="border-radius:.55rem;border:1px solid rgba(0,0,0,.08);font-size:13.5px">
                                    <li>
                                        <a class="dropdown-item py-2 {{ $role !== 'admin' ? 'disabled text-muted' : '' }}"
                                            href="#" wire:click.prevent="$set('position', 'husband')">
                                            Husband
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 {{ $role === 'admin' ? 'disabled text-muted' : '' }}"
                                            href="#" wire:click.prevent="$set('position', 'wife')">
                                            Wife
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 {{ $role === 'admin' ? 'disabled text-muted' : '' }}"
                                            href="#" wire:click.prevent="$set('position', 'child')">
                                            Child
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @error('position')
                                <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Password --}}
                    <div class="mb-0">
                        <label class="d-block mb-1"
                            style="font-size:.68rem;font-weight:700;color:#adb5bd;text-transform:uppercase;letter-spacing:.07em">
                            Password
                        </label>
                        <div class="d-flex align-items-center"
                            style="background:#f8f9fa;border-radius:.55rem;border:1.5px solid transparent;overflow:hidden;transition:border .15s,background .15s"
                            onfocusin="this.style.borderColor='var(--sk-primary)';this.style.background='#fff'"
                            onfocusout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
                            <span class="px-3" style="color:#adb5bd;font-size:14px;flex-shrink:0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input wire:model="password" type="password"
                                class="border-0 shadow-none form-control @error('password') is-invalid @enderror"
                                style="background:transparent;font-size:13.5px;padding:9px 10px 9px 0"
                                placeholder="••••••••" autocomplete="new-password">
                        </div>
                        @if ($isEditMode)
                            <div class="mt-1 d-flex align-items-center gap-1"
                                style="font-size:11px;color:var(--sk-primary)">
                                <i class="bi bi-info-circle"></i> Kosongkan jika tidak diubah
                            </div>
                        @endif
                        @error('password')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-4 pb-4 d-flex align-items-center justify-content-between gap-2">
                    @if (!$isEditMode)
                        <button type="button" wire:click="resetForm"
                            class="btn btn-link text-decoration-none fw-bold" style="color:#dc3545;font-size:13.5px">
                            <i class="bi bi-trash3"></i> Bersihkan
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" wire:click="closeModal" data-bs-dismiss="modal"
                            class="btn btn-link text-decoration-none fw-bold"
                            style="color:#adb5bd;font-size:13.5px">Tutup</button>
                        <button type="submit" class="btn btn-success rounded-pill d-flex align-items-center gap-2"
                            style="background:var(--sk-primary-gradient);border:none;font-size:13.5px;font-weight:700;padding:8px 20px">
                            <i class="bi bi-check-lg"></i>
                            {{ $isEditMode ? 'Simpan Perubahan' : 'Daftarkan User' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
