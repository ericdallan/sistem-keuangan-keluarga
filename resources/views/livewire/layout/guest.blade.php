<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SKK.Digital - Portal Akses</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sk-digital.css') }}">

    <style>
        /* ============================================
           Guest Layout — Specific Styles
           ============================================ */

        body {
            min-height: 100vh;
        }

        .split-left {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .split-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .split-left::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .split-right {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 2rem;
        }

        .info-content {
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .feature-item .feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .card {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08) !important;
            width: 100%;
            max-width: 420px;
        }

        .input-group {
            transition: box-shadow 0.2s ease;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15) !important;
            border-color: var(--primary-color) !important;
        }

        .form-label {
            font-size: 0.75rem !important;
            letter-spacing: 0.05em;
        }

        .form-check-input:checked {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .hover-sk-primary:hover {
            color: var(--primary-color) !important;
            transition: 0.3s;
        }

        @media (max-width: 991.98px) {
            .split-left {
                min-height: auto;
                padding: 2rem;
                text-align: center;
            }

            .split-right {
                min-height: auto;
                padding: 2rem 1rem;
            }

            .feature-item {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Left Side: Info -->
            <div class="col-lg-5 split-left text-white">
                <div class="info-content">
                    <div class="mb-5">
                        <a href="/" wire:navigate class="text-decoration-none text-white">
                            <div class="d-inline-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-wallet2 fs-2"></i>
                                <h3 class="fw-extrabold mb-0" style="letter-spacing: -1px;">
                                    SKK<span style="opacity: 0.8;">.Digital</span>
                                </h3>
                            </div>
                        </a>
                        <h1 class="fw-bold mb-3" style="font-size: 2.2rem; line-height: 1.2;">
                            Kelola Finansial<br>Keluarga dengan Mudah
                        </h1>
                        <p class="opacity-75 mb-0" style="font-size: 1rem; max-width: 400px;">
                            Sistem pencatatan keuangan keluarga dengan alur approval terstruktur. Transparansi antara
                            Suami, Istri, dan Anak dalam satu genggaman.
                        </p>
                    </div>

                    <div class="mt-4">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Aman & Terstruktur</h6>
                                <p class="small opacity-75 mb-0">Alur approval otomatis dengan manajemen role</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Laporan Real-time</h6>
                                <p class="small opacity-75 mb-0">Pantau saldo dan pengajuan dana secara langsung</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Bukti Tercatat</h6>
                                <p class="small opacity-75 mb-0">Upload evidence pengeluaran dalam format JPG/PDF</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-7 split-right">
                <div class="w-100 d-flex justify-content-center">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    {{--  Chart.js untuk saat navigate ke dashboard --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <script>
        window.SKK = window.SKK || {};
        window.SKK.charts = window.SKK.charts || {};

        window.SKK.destroyAllCharts = function() {
            Object.values(window.SKK.charts).forEach(chart => {
                if (chart && typeof chart.destroy === 'function') chart.destroy();
            });
            window.SKK.charts = {};
        };

        window.SKK.registerChart = function(name, chartInstance) {
            if (window.SKK.charts[name]) window.SKK.charts[name].destroy();
            window.SKK.charts[name] = chartInstance;
        };

        document.addEventListener('livewire:navigating', function() {
            window.SKK.destroyAllCharts();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });

        function skToast(message, type = 'success') {
            const container = document.getElementById('sk-toast-container');
            if (!container) return;
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
            const inner = document.createElement('div');
            inner.innerHTML = `<div class="d-flex align-items-center gap-3 p-3 shadow-sm"
                style="background:#fff;border-radius:.875rem;border-left:4px solid ${c.border};
                       cursor:pointer;opacity:0;transition:opacity .3s,transform .3s;
                       transform:translateY(-8px);min-width:280px;max-width:320px">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                    style="width:34px;height:34px;background:${c.bg};color:${c.color};font-size:1rem">
                    <i class="bi ${c.icon}"></i>
                </div>
                <span class="fw-semibold flex-grow-1" style="font-size:.85rem;color:#2d3436">${message}</span>
                <i class="bi bi-x flex-shrink-0" style="font-size:1rem;color:#adb5bd"></i>
            </div>`;
            const el = inner.firstElementChild;
            container.appendChild(el);
            el.addEventListener('click', () => dismiss(el));
            requestAnimationFrame(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
            const t = setTimeout(() => dismiss(el), 3500);

            function dismiss(e) {
                clearTimeout(t);
                e.style.opacity = '0';
                e.style.transform = 'translateY(-8px)';
                setTimeout(() => e.remove(), 300);
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', ({
                message,
                type
            }) => skToast(message, type ?? 'success'));
        });
    </script>

    <div id="sk-toast-container"
        style="position:fixed;top:1.5rem;right:1.5rem;z-index:9999;
               display:flex;flex-direction:column;gap:.5rem">
    </div>
</body>

</html>
