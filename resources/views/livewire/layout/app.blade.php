<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SKK.Digital') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sk-digital.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sk-layout.css') }}">
</head>

<body x-data="skLayout()" @keydown.escape.window="closeMobile()">

    {{-- Overlay --}}
    <div class="sk-overlay" :class="{ 'show': mobileOpen }" @click="closeMobile()"></div>

    {{-- Sidebar --}}
    <livewire:layout.sidebar />

    {{-- Navbar --}}
    <livewire:layout.navbar :title="$title ?? ''" />

    {{-- Main --}}
    <main class="sk-main" :class="{ 'collapsed': collapsed }">
        @if (isset($header))
            <div class="mb-4">{{ $header }}</div>
        @endif
        {{ $slot }}
    </main>

    {{-- Global tooltip (dipakai sidebar saat collapsed) --}}
    <div id="sk-global-tooltip"
        style="position:fixed; display:none; background:#2d3436; color:#fff;
               font-size:0.78rem; font-weight:600; padding:0.3rem 0.7rem;
               border-radius:0.45rem; pointer-events:none; z-index:9999;
               white-space:nowrap; box-shadow:0 4px 12px rgba(0,0,0,0.18);
               font-family:'Figtree',sans-serif; transition: opacity .1s;">
    </div>
    {{-- Global Toast Container --}}
    <div id="sk-toast-container"
        style="position:fixed;
           top:calc(var(--sk-navbar-h) + .75rem);
           right:1.5rem;
           z-index:9999;
           display:flex;
           flex-direction:column;
           gap:.5rem">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-modal', ({
                modal
            }) => {
                const el = document.getElementById(modal);
                if (el) bootstrap.Modal.getOrCreateInstance(el).show();
            });

            Livewire.on('close-modal', ({
                modal
            }) => {
                const el = document.getElementById(modal);
                if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
            });
        });

        function skLayout() {
            return {
                collapsed: localStorage.getItem('sk_sidebar') === 'collapsed',
                mobileOpen: false,
                toggle() {
                    if (window.innerWidth < 992) {
                        this.mobileOpen = !this.mobileOpen;
                    } else {
                        this.collapsed = !this.collapsed;
                        localStorage.setItem('sk_sidebar', this.collapsed ? 'collapsed' : 'expanded');
                    }
                },
                closeMobile() {
                    this.mobileOpen = false;
                },
            }
        }

        function skShowTooltip(event, label) {
            if (!document.querySelector('.sk-sidebar.collapsed')) return;
            const rect = event.currentTarget.getBoundingClientRect();
            const tip = document.getElementById('sk-global-tooltip');
            tip.textContent = label;
            tip.style.display = 'block';
            tip.style.top = (rect.top + rect.height / 2 - 14) + 'px';
            tip.style.left = (rect.right + 10) + 'px';
        }

        function skHideTooltip() {
            document.getElementById('sk-global-tooltip').style.display = 'none';
        }

        function skToast(message, type = 'success') {
            const config = {
                success: {
                    bg: '#d1e7dd',
                    border: '#198754',
                    color: '#198754',
                    icon: 'bi-check-circle-fill'
                },
                error: {
                    bg: '#f8d7da',
                    border: '#dc3545',
                    color: '#dc3545',
                    icon: 'bi-x-circle-fill'
                },
                warning: {
                    bg: '#fff3cd',
                    border: '#ffc107',
                    color: '#856404',
                    icon: 'bi-exclamation-triangle-fill'
                },
                info: {
                    bg: '#cff4fc',
                    border: '#0dcaf0',
                    color: '#055160',
                    icon: 'bi-info-circle-fill'
                },
            };

            const c = config[type] ?? config.success;

            const el = document.createElement('div');
            el.innerHTML = `
                <div class="d-flex align-items-center gap-3 p-3 shadow-sm"
                    style="background:#fff;border-radius:.875rem;border-left:4px solid ${c.border};
                        cursor:pointer;opacity:0;transition:opacity .3s,transform .3s;
                        transform:translateY(-8px);min-width:280px;max-width:320px">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                        style="width:34px;height:34px;background:${c.bg};color:${c.color};font-size:1rem">
                        <i class="bi ${c.icon}"></i>
                    </div>
                    <span class="fw-semibold flex-grow-1" style="font-size:.85rem;color:#2d3436">${message}</span>
                    <i class="bi bi-x flex-shrink-0" style="font-size:1rem;color:#adb5bd"></i>
                </div>
            `;

            const inner = el.firstElementChild;
            const container = document.getElementById('sk-toast-container');
            container.appendChild(inner);

            inner.addEventListener('click', () => dismiss(inner));

            requestAnimationFrame(() => {
                inner.style.opacity = '1';
                inner.style.transform = 'translateY(0)';
            });

            const timer = setTimeout(() => dismiss(inner), 3500);

            function dismiss(el) {
                clearTimeout(timer);
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 300);
            }
        }

        // Listener untuk event dari Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', ({
                message,
                type
            }) => {
                skToast(message, type ?? 'success');
            });
        });
    </script>
</body>

</html>
