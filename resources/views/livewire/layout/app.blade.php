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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sk-primary: #198754;
            --sk-primary-hover: #157347;
            --sk-primary-light: #d1e7dd;
            --sk-primary-gradient: linear-gradient(135deg, #198754 0%, #20c997 100%);
            --sk-bg: #f0f4f8;
            --sk-text: #2d3436;
            --sk-sidebar-w: 255px;
            --sk-sidebar-cw: 64px;
            --sk-navbar-h: 60px;
            --sk-transition: 0.22s ease;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Figtree', sans-serif;
            background: var(--sk-bg);
            color: var(--sk-text);
            height: 100%;
            margin: 0;
        }

        /* ───── Sidebar ───── */
        .sk-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sk-sidebar-w);
            background: #fff;
            border-right: 1px solid rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: width var(--sk-transition);
            /* overflow visible agar tooltip tidak terpotong */
            overflow: visible;
        }

        /* Clip brand text overflow */
        .sk-brand {
            overflow: hidden;
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }

        .sk-sidebar.collapsed {
            overflow-x: hidden;
        }

        .sk-sidebar.collapsed {
            width: var(--sk-sidebar-cw);
        }

        @media (max-width: 991.98px) {
            .sk-sidebar {
                transform: translateX(-100%);
                width: var(--sk-sidebar-w) !important;
                transition: transform var(--sk-transition);
                overflow: hidden;
            }

            .sk-sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        /* Brand */
        .sk-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0 1.1rem;
            height: var(--sk-navbar-h);
            border-bottom: 1px solid rgba(0, 0, 0, 0.07);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sk-brand-icon {
            font-size: 1.4rem;
            color: var(--sk-primary);
            flex-shrink: 0;
            width: 24px;
            text-align: center;
        }

        .sk-brand-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--sk-text);
            opacity: 1;
            transition: opacity var(--sk-transition);
        }

        .sk-brand-text em {
            color: var(--sk-primary);
            font-style: normal;
        }

        .sk-sidebar.collapsed .sk-brand-text {
            opacity: 0;
            pointer-events: none;
        }

        /* Nav scroll area */
        .sk-sidebar-body {
            flex: 1;
            padding: 0.875rem 0.625rem;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.12) transparent;
        }

        .sk-sidebar-body::-webkit-scrollbar {
            width: 4px;
        }

        .sk-sidebar-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .sk-sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.12);
            border-radius: 4px;
        }

        /* Saat collapsed: sembunyikan scrollbar agar icon tidak tertutup */
        .sk-sidebar.collapsed .sk-sidebar-body {
            overflow-y: hidden;
            scrollbar-width: none;
        }

        .sk-sidebar.collapsed .sk-sidebar-body::-webkit-scrollbar {
            display: none;
        }

        /* Section label */
        .sk-nav-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #adb5bd;
            padding: 0.5rem 0.625rem 0.25rem;
            white-space: nowrap;
            opacity: 1;
            transition: opacity var(--sk-transition);
            position: relative;
        }

        /* Saat collapsed: label hilang, tampilkan garis pemisah */
        .sk-sidebar.collapsed .sk-nav-label {
            opacity: 0;
            height: 1px;
            background: rgba(0, 0, 0, 0.08);
            margin: 0.5rem 0.625rem;
            padding: 0;
            overflow: hidden;
        }

        /* Pseudo-element tidak bisa dipakai, jadi override via wrapper trick */
        .sk-sidebar.collapsed .sk-nav-label::after {
            content: none;
        }

        /* Nav link */
        .sk-nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.575rem 0.625rem;
            border-radius: 0.55rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6c757d;
            text-decoration: none;
            white-space: nowrap;
            margin-bottom: 0.125rem;
            position: relative;
            transition: background 0.15s, color 0.15s;
        }

        .sk-nav-link i {
            font-size: 1.05rem;
            flex-shrink: 0;
            width: 22px;
            text-align: center;
        }

        .sk-nav-link .sk-link-text {
            opacity: 1;
            transition: opacity var(--sk-transition);
        }

        .sk-sidebar.collapsed .sk-nav-link .sk-link-text {
            opacity: 0;
        }

        .sk-nav-link:hover,
        .sk-nav-link.active {
            background: var(--sk-primary-light);
            color: var(--sk-primary);
        }

        /* Tooltip on collapsed — handled by JS global tooltip */

        /* ───── Navbar ───── */
        .sk-navbar {
            position: fixed;
            top: 0;
            left: var(--sk-sidebar-w);
            right: 0;
            height: var(--sk-navbar-h);
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.07);
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 0.875rem;
            z-index: 1030;
            transition: left var(--sk-transition);
        }

        .sk-navbar.collapsed {
            left: var(--sk-sidebar-cw);
        }

        @media (max-width: 991.98px) {
            .sk-navbar {
                left: 0 !important;
            }
        }

        /* ───── Main ───── */
        .sk-main {
            margin-left: var(--sk-sidebar-w);
            margin-top: var(--sk-navbar-h);
            padding: 1.75rem;
            min-height: calc(100vh - var(--sk-navbar-h));
            transition: margin-left var(--sk-transition);
        }

        .sk-main.collapsed {
            margin-left: var(--sk-sidebar-cw);
        }

        @media (max-width: 991.98px) {
            .sk-main {
                margin-left: 0 !important;
            }
        }

        /* ───── Overlay ───── */
        .sk-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1039;
        }

        .sk-overlay.show {
            display: block;
        }

        /* ───── Navbar elements ───── */
        .sk-toggle {
            background: none;
            border: none;
            padding: 0.4rem;
            border-radius: 0.5rem;
            color: #6c757d;
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
            flex-shrink: 0;
            transition: background 0.15s, color 0.15s;
        }

        .sk-toggle:hover {
            background: var(--sk-primary-light);
            color: var(--sk-primary);
        }

        .sk-toggle i {
            display: block;
        }

        .sk-page-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--sk-text);
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sk-navbar-right {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-left: auto;
        }

        .sk-icon-btn {
            background: none;
            border: none;
            padding: 0.4rem 0.5rem;
            border-radius: 0.5rem;
            color: #6c757d;
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            position: relative;
            transition: background 0.15s, color 0.15s;
        }

        .sk-icon-btn:hover {
            background: var(--sk-primary-light);
            color: var(--sk-primary);
        }

        .sk-notif-dot {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 7px;
            height: 7px;
            background: #dc3545;
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .sk-user-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.3rem 0.5rem;
            border-radius: 0.6rem;
            cursor: pointer;
            transition: background 0.15s;
        }

        .sk-user-btn:hover {
            background: var(--sk-primary-light);
        }

        .sk-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--sk-primary-light);
            color: var(--sk-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .sk-user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--sk-text);
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 575.98px) {
            .sk-user-name {
                display: none;
            }
        }

        .sk-chevron {
            font-size: 0.65rem;
            color: #adb5bd;
            transition: transform 0.18s;
        }

        /* Dropdown */
        .sk-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 195px;
            background: #fff;
            border-radius: 0.875rem;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.11);
            padding: 0.4rem;
            z-index: 1060;
        }

        .sk-drop-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.575rem 0.875rem;
            border-radius: 0.55rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--sk-text);
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background 0.15s;
        }

        .sk-drop-item:hover {
            background: var(--sk-primary-light);
            color: var(--sk-primary);
        }

        .sk-drop-item.danger {
            color: #dc3545;
        }

        .sk-drop-item.danger:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        .sk-nav-separator {
            display: contents;
        }

        .sk-nav-separator .sk-nav-label {
            display: block;
        }

        .sk-sidebar.collapsed .sk-nav-separator {
            display: block;
            height: 1px;
            background: rgba(0, 0, 0, 0.08);
            margin: 0.5rem 0.625rem;
            overflow: hidden;
        }

        .sk-sidebar.collapsed .sk-nav-separator .sk-nav-label {
            display: none;
        }

        /* ───── Notifikasi Dropdown ───── */
        .sk-notif-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 340px;
            background: #fff;
            border-radius: 0.875rem;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.11);
            z-index: 1060;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .sk-notif-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1rem 0.625rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--sk-text);
        }

        .sk-notif-readall {
            background: none;
            border: none;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--sk-primary);
            cursor: pointer;
            padding: 0;
        }

        .sk-notif-readall:hover {
            text-decoration: underline;
        }

        .sk-notif-list {
            max-height: 320px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.1) transparent;
        }

        .sk-notif-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            transition: background 0.15s;
            position: relative;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .sk-notif-item:hover {
            background: #f8f9fa;
        }

        .sk-notif-item.unread {
            background: var(--sk-primary-light);
        }

        .sk-notif-item.unread:hover {
            background: #c3e6cb;
        }

        .sk-notif-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sk-notif-content {
            flex: 1;
            min-width: 0;
        }

        .sk-notif-title {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--sk-text);
            margin-bottom: 0.15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sk-notif-body {
            font-size: 0.775rem;
            color: #6c757d;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sk-notif-time {
            font-size: 0.7rem;
            color: #adb5bd;
            margin-top: 0.2rem;
        }

        .sk-notif-unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--sk-primary);
            flex-shrink: 0;
            margin-top: 4px;
        }

        .sk-notif-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 2rem 1rem;
            color: #adb5bd;
            font-size: 0.875rem;
        }

        .sk-notif-empty i {
            font-size: 1.75rem;
        }

        .sk-notif-footer {
            padding: 0.625rem 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            text-align: center;
        }

        .sk-notif-all-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--sk-primary);
            text-decoration: none;
        }

        .sk-notif-all-link:hover {
            text-decoration: underline;
        }

        /* ───── Misc ───── */
        .sk-text-primary {
            color: var(--sk-primary) !important;
        }

        .sk-bg-primary-subtle {
            background-color: var(--sk-primary-light) !important;
        }

        [x-cloak] {
            display: none !important;
        }

        /* ═══════════════════════════════════════════════════════════════ */
        /* ── Livewire Modal Override: Force ke tengah viewport ── */
        /* ═══════════════════════════════════════════════════════════════ */

        /* Override margin dari parent .sk-main untuk modal */
        .livewire-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 9999 !important;
            background: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(6px);
        }

        .livewire-modal-content {
            background: #fff;
            border-radius: 1rem;
            width: 100%;
            max-width: 440px;
            max-height: 90vh;
            overflow-y: auto;
            margin: 1rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .livewire-modal-overlay * {
            transform: none !important;
        }
    </style>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
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
    </script>
</body>

</html>
