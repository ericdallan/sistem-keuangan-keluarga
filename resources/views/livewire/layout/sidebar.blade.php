<aside class="sk-sidebar" :class="{ 'collapsed': collapsed, 'mobile-open': mobileOpen }">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" wire:navigate class="sk-brand">
        <i class="bi bi-wallet2 sk-brand-icon"></i>
        <span class="sk-brand-text">SKK<em>.Digital</em></span>
    </a>

    {{-- Nav --}}
    <div class="sk-sidebar-body">

        @php
            $menuUtama = [
                [
                    'route' => 'dashboard',
                    'icon' => 'bi-speedometer2',
                    'label' => 'Dashboard',
                    'match' => 'dashboard',
                ],
            ];

            if (auth()->user()->role === 'admin') {
                $pengelolaan = [
                    [
                        'route' => 'income.index',
                        'icon' => 'bi-arrow-down-circle',
                        'label' => 'Pemasukan',
                        'match' => 'income.*',
                    ],
                    [
                        'route' => 'expenses.index',
                        'icon' => 'bi-arrow-up-circle',
                        'label' => 'Pengeluaran',
                        'match' => 'expenses.*',
                    ],
                    [
                        'route' => 'fund-requests.index',
                        'icon' => 'bi-cash-stack',
                        'label' => 'Permintaan Dana',
                        'match' => 'fund-requests.*',
                    ],
                ];
            } else {
                $pengelolaan = [
                    [
                        'route' => 'expenses.index',
                        'icon' => 'bi-arrow-up-circle',
                        'label' => 'Pengeluaran Saya',
                        'match' => 'my-expenses.*',
                    ],
                    [
                        'route' => 'fund-requests.index',
                        'icon' => 'bi-cash-stack',
                        'label' => 'Pengajuan Dana Saya',
                        'match' => 'my-fund-requests.*',
                    ],
                ];
            }

            $laporan = [
                [
                    'route' => 'statistics.index',
                    'icon' => 'bi-bar-chart-line',
                    'label' => 'Statistik',
                    'match' => 'statistics.*',
                ],
                [
                    'route' => 'reports.index',
                    'icon' => 'bi-file-earmark-text',
                    'label' => 'Laporan',
                    'match' => 'reports.*',
                ],
            ];

            $pengaturan =
                auth()->user()->role === 'admin'
                    ? [
                        [
                            'route' => 'users.index',
                            'icon' => 'bi-people',
                            'label' => 'Kelola Pengguna',
                            'match' => 'users.*',
                        ],
                    ]
                    : [];
        @endphp

        {{-- ── Menu Utama ── --}}
        <div class="sk-nav-separator">
            <div class="sk-nav-label">Menu Utama</div>
        </div>

        @foreach ($menuUtama as $link)
            <a href="{{ route($link['route']) }}" wire:navigate
                class="sk-nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}"
                @mouseenter="skShowTooltip($event, '{{ $link['label'] }}')" @mouseleave="skHideTooltip()">
                <i class="bi {{ $link['icon'] }}"></i>
                <span class="sk-link-text">{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- ── Pengelolaan ── --}}
        <div class="sk-nav-separator">
            <div class="sk-nav-label">Pengelolaan</div>
        </div>

        @foreach ($pengelolaan as $link)
            <a href="{{ route($link['route']) }}" wire:navigate
                class="sk-nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}"
                @mouseenter="skShowTooltip($event, '{{ $link['label'] }}')" @mouseleave="skHideTooltip()">
                <i class="bi {{ $link['icon'] }}"></i>
                <span class="sk-link-text">{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- ── Laporan ── --}}
        <div class="sk-nav-separator">
            <div class="sk-nav-label">Laporan</div>
        </div>

        @foreach ($laporan as $link)
            <a href="{{ route($link['route']) }}" wire:navigate
                class="sk-nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}"
                @mouseenter="skShowTooltip($event, '{{ $link['label'] }}')" @mouseleave="skHideTooltip()">
                <i class="bi {{ $link['icon'] }}"></i>
                <span class="sk-link-text">{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- ── Pengaturan (Admin only) ── --}}
        @if (count($pengaturan))
            <div class="sk-nav-separator">
                <div class="sk-nav-label">Pengaturan</div>
            </div>

            @foreach ($pengaturan as $link)
                <a href="{{ route($link['route']) }}" wire:navigate
                    class="sk-nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}"
                    @mouseenter="skShowTooltip($event, '{{ $link['label'] }}')" @mouseleave="skHideTooltip()">
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span class="sk-link-text">{{ $link['label'] }}</span>
                </a>
            @endforeach
        @endif

    </div>

</aside>
