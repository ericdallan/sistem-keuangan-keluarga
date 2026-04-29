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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #198754 0%, #20c997 100%);
            --primary-color: #198754;
            --primary-hover: #157347;
            --primary-light: #d1e7dd;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #ffffff;
            color: #2d3436;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Reusable Button Styles - Bisa dipakai di blade lain */
        .btn-sk-primary {
            background: var(--primary-gradient) !important;
            border: none !important;
            padding: 12px 30px !important;
            border-radius: 50px !important;
            font-weight: 700 !important;
            color: white !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3) !important;
        }

        .btn-sk-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4) !important;
            color: white !important;
        }

        .btn-sk-outline {
            background: transparent !important;
            border: 2px solid var(--primary-color) !important;
            padding: 10px 28px !important;
            border-radius: 50px !important;
            font-weight: 700 !important;
            color: var(--primary-color) !important;
            transition: all 0.3s ease !important;
        }

        .btn-sk-outline:hover {
            background: var(--primary-color) !important;
            color: white !important;
            transform: translateY(-2px);
        }

        .text-sk-primary {
            color: var(--primary-color) !important;
        }

        .bg-sk-primary-subtle {
            background-color: var(--primary-light) !important;
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

        .feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
