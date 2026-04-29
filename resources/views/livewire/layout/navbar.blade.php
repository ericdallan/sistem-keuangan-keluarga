<header class="sk-navbar" :class="{ 'collapsed': collapsed }">

    {{-- Toggle --}}
    <button class="sk-toggle" @click="toggle()" aria-label="Toggle sidebar">
        <i class="bi" :class="collapsed ? 'bi-layout-sidebar' : 'bi-layout-sidebar-inset'"></i>
    </button>

    {{-- Page title --}}
    <span class="sk-page-title" x-data="{ pageTitle: '{{ $title ?? ucfirst(request()->segment(1) ?? 'Dashboard') }}' }" x-text="pageTitle"
        @page-title-updated.window="pageTitle = $event.detail.title">
    </span>

    {{-- Right side --}}
    <div class="sk-navbar-right">

        {{-- Notifikasi --}}
        <div class="position-relative" x-data="{ open: false }" @click.away="open = false">

            <button class="sk-icon-btn" @click="open = !open" title="Notifikasi">
                <i class="bi bi-bell"></i>
                @if ($unreadCount > 0)
                    <span class="sk-notif-dot"></span>
                @endif
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                class="sk-notif-dropdown">

                {{-- Header --}}
                <div class="sk-notif-header">
                    <span class="fw-700">Notifikasi</span>
                    @if ($unreadCount > 0)
                        <button wire:click="markAllRead" class="sk-notif-readall">
                            Tandai semua dibaca
                        </button>
                    @endif
                </div>

                {{-- List --}}
                <div class="sk-notif-list">
                    @forelse ($notifications as $notif)
                        <button wire:click="markRead({{ $notif['id'] }})"
                            class="sk-notif-item {{ !$notif['read'] ? 'unread' : '' }}">
                            <div class="sk-notif-icon-wrap {{ $notif['color'] }}">
                                <i class="bi {{ $notif['icon'] }}"></i>
                            </div>
                            <div class="sk-notif-content">
                                <div class="sk-notif-title">{{ $notif['title'] }}</div>
                                <div class="sk-notif-body">{{ $notif['body'] }}</div>
                                <div class="sk-notif-time">{{ $notif['time'] }}</div>
                            </div>
                            @if (!$notif['read'])
                                <span class="sk-notif-unread-dot"></span>
                            @endif
                        </button>
                    @empty
                        <div class="sk-notif-empty">
                            <i class="bi bi-bell-slash"></i>
                            <span>Tidak ada notifikasi</span>
                        </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="sk-notif-footer">
                    <a href="#" class="sk-notif-all-link">Lihat semua notifikasi</a>
                </div>

            </div>
        </div>

        {{-- User Dropdown --}}
        <div class="position-relative" x-data="{ open: false }" @click.away="open = false">
            <button class="sk-user-btn" @click="open = !open">
                <div class="sk-avatar"><i class="bi bi-person-fill"></i></div>
                <span class="sk-user-name" x-text="{{ json_encode(auth()->user()->name) }}"
                    x-on:profile-updated.window="$el.textContent = $event.detail.name">
                </span>
                <i class="bi bi-chevron-down sk-chevron" :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                class="sk-dropdown">

                <a href="{{ route('profile.edit') }}" wire:navigate class="sk-drop-item">
                    <i class="bi bi-person-circle"></i> Profil
                </a>

                <hr class="my-1 mx-2" style="border-color:rgba(0,0,0,0.07)">

                <button wire:click="logout" class="sk-drop-item danger">
                    <i class="bi bi-box-arrow-right"></i> Log Out
                </button>
            </div>
        </div>

    </div>
</header>
