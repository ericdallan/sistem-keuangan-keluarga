<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SKK.Digital - Smart Family Finance</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sk-digital.css') }}">
    <style>
        .navbar {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            letter-spacing: -2px;
            line-height: 1.1;
            font-weight: 800;
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .mockup-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.5s ease;
        }

        .step-card {
            border: 1px solid #eee;
            border-radius: 20px;
            padding: 2rem;
            transition: 0.3s;
            background: #fff;
        }

        .step-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(25, 135, 84, 0.05);
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        footer {
            background: #0f172a;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-decoration-none" href="#">
                <i class="bi bi-wallet2 text-sk-primary me-2"></i>SKK<span class="text-sk-primary">.Digital</span>
            </a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    <livewire:welcome.navigation />
                @endif
            </div>
        </div>
    </nav>

    <header class="py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge bg-sk-primary-subtle text-sk-primary mb-3 px-3 py-2 rounded-pill fw-bold">
                        🚀 Financial Transparency System
                    </span>
                    <h1 class="hero-title mb-4">
                        Kelola Finansial <br><span class="text-gradient">Satu Pintu.</span>
                    </h1>
                    <p class="lead text-muted mb-5">
                        Sistem pencatatan keuangan keluarga dengan alur <strong>Approval</strong> yang terstruktur.
                        Transparansi antara Suami, Istri, dan Anak dalam satu genggaman.
                    </p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('login') }}" class="btn-sk-primary btn-lg rounded-pill shadow-lg fw-bold">
                            Buka Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#workflow" class="btn-sk-outline btn-lg rounded-pill fw-bold">
                            Cara Kerja
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mockup-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0"><i class="bi bi-activity me-2 text-sk-primary"></i>Live Preview
                            </h6>
                            <span class="badge bg-success-subtle text-success rounded-pill px-3">Saldo Aktif</span>
                        </div>
                        <div class="p-4 rounded-4 mb-4" style="background: #f8f9fa;">
                            <small class="text-muted d-block mb-1">Total Kas Keluarga</small>
                            <h3 class="fw-bold mb-0 text-sk-primary">Rp 15.750.000</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-transparent px-0 border-0 d-flex align-items-center">
                                <div class="feature-icon me-3 bg-warning-subtle text-warning"><i
                                        class="bi bi-hourglass-split"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold small">Pengajuan Dana Anak</h6>
                                    <small class="text-muted">Buku Sekolah - Rp 250.000</small>
                                </div>
                                <span class="badge rounded-pill bg-warning text-dark small">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="workflow" class="py-5 mt-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Alur Kerja <span class="text-sk-primary">Sistem</span></h2>
                <p class="text-muted">Proses approval otomatis sesuai instruksi teknis</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-card h-100">
                        <div class="step-number">1</div>
                        <h5 class="fw-bold">Request & Record</h5>
                        <p class="text-muted small mb-0">Istri atau Anak menginput pengeluaran (dengan bukti) atau
                            mengajukan dana tambahan melalui portal user.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card h-100">
                        <div class="step-number">2</div>
                        <h5 class="fw-bold">Admin Review</h5>
                        <p class="text-muted small mb-0">Suami (Admin) meninjau daftar pengajuan. Admin berhak melakukan
                            <strong>Approve</strong> atau <strong>Reject</strong>.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card h-100 border-success shadow-sm">
                        <div class="step-number">3</div>
                        <h5 class="fw-bold text-sk-primary">Auto Settlement</h5>
                        <p class="text-muted small mb-0">Jika dana disetujui, nominal otomatis masuk ke <strong>Master
                                Uang Masuk</strong> dan menambah saldo keluarga.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="roles" class="py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <h2 class="fw-bold mb-4">Manajemen Role & <span class="text-sk-primary">Hak Akses</span></h2>
                    <div class="d-flex mb-4">
                        <div class="feature-icon me-3"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Admin (Suami)</h6>
                            <p class="small text-muted mb-0">Kelola master pemasukan, semua data user, dan otoritas
                                penuh approval.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="feature-icon me-3 bg-danger-subtle text-danger"><i class="bi bi-people"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">User (Istri & Anak)</h6>
                            <p class="small text-muted mb-0">Kelola data pribadi, upload bukti pengeluaran, dan
                                pengajuan dana.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-4 border rounded-4 text-center">
                                <i class="bi bi-file-earmark-pdf fs-2 text-danger mb-2"></i>
                                <h6 class="fw-bold mb-0">Evidence Upload</h6>
                                <small class="text-muted">JPG/PDF Support</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 border rounded-4 text-center">
                                <i class="bi bi-graph-up-arrow fs-2 text-success mb-2"></i>
                                <h6 class="fw-bold mb-0">Auto Reporting</h6>
                                <small class="text-muted">Monthly Summary</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-white-50 py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <a class="navbar-brand fw-bold fs-4 text-white text-decoration-none" href="#">
                        SKK<span class="text-sk-primary">.Digital</span>
                    </a>
                    <p class="mt-2 small">Aplikasi Test Interview - Fullstack Web Developer</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-white">Dev by <strong>Subagja Eric Dallan</strong></p>
                    <p class="small mb-0">Built with Laravel 13, Livewire 3 & Bootstrap 5</p>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-25">
            <p class="text-center small mb-0">&copy; 2026 PT Niramas Utama (INACO) Recruitment Process.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
