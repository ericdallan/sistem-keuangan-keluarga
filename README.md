<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>
<h1 align="center">Sistem Keuangan Keluarga</h1>
<p align="center">
  Aplikasi Manajemen Keuangan Keluarga dengan Sistem Approval Workflow<br>
  <strong>Dibuat untuk memenuhi tugas technical interview</strong><br>
  <strong>Full Stack Developer - PT. Niramas Utama (INACO)</strong>
</p>
<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Livewire-3-4F46E5?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 3">
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
</p>
<h2>About This Project</h2>
<p><strong>Sistem Keuangan Keluarga</strong> adalah aplikasi berbasis Laravel 13 yang dirancang untuk membantu keluarga mengelola keuangan secara transparan dan terstruktur. Aplikasi ini menyediakan pencatatan pemasukan, pengeluaran, serta sistem pengajuan dana dengan <strong>approval workflow</strong> yang jelas antara Admin (Suami) dan User (Istri/Anak).</p>
<p>Proyek ini dibangun dengan fokus pada clean code menggunakan <strong>Class-Based Livewire Components</strong>, RBAC dengan Laravel Policy, dan otomatisasi transaksi database.</p>
<h2>Fitur Utama</h2>
<ul>
<li>Role-Based Access Control (RBAC) menggunakan Laravel Policy</li>
<li>Approval Workflow untuk pengajuan dana dan pengeluaran</li>
<li>Otomatisasi Finansial (data pengajuan dana otomatis menjadi pemasukan setelah disetujui)</li>
<li>Upload bukti transaksi (Gambar & PDF)</li>
<li>Class-Based Livewire 3 Components</li>
<li>Validasi form ketat dan proteksi data sensitif</li>
<li>Antarmuka responsif dengan Bootstrap 5</li>
</ul>
<h2>Tech Stack</h2>
<table>
  <tr><th>Kategori</th><th>Teknologi</th></tr>
  <tr><td><strong>Framework</strong></td><td>Laravel 13</td></tr>
  <tr><td><strong>PHP</strong></td><td>^8.3</td></tr>
  <tr><td><strong>Database</strong></td><td>MySQL</td></tr>
  <tr><td><strong>Frontend</strong></td><td>Blade Templating + Bootstrap 5</td></tr>
  <tr><td><strong>Interactivity</strong></td><td>Livewire 3 (Class-Based Components)</td></tr>
  <tr><td><strong>Authentication</strong></td><td>Laravel Breeze</td></tr>
</table>
<h2>Alur Kerja Aplikasi</h2>
<ol>
<li>User membuat pengajuan dana atau mencatat pengeluaran</li>
<li>Status otomatis menjadi <strong>Pending</strong></li>
<li>Admin melakukan review dan approval/reject</li>
<li>Jika disetujui, data pengajuan dana otomatis dipindahkan ke Master Pemasukan</li>
<li>Semua transaksi tercatat lengkap dengan bukti</li>
</ol>
<h2>Cara Instalasi</h2>
<h3>1. Clone Repository</h3>
<pre>
git clone [link-repo-anda]
cd sistem-keuangan-keluarga
</pre>
<h3>2. Install Dependensi</h3>
<pre>
composer install
npm install && npm run build
</pre>
<h3>3. Konfigurasi Environment</h3>
<pre>
cp .env.example .env
</pre>
<p>Sesuaikan konfigurasi database di file <code>.env</code>:</p>
<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_keuangan_keluarga
DB_USERNAME=root
DB_PASSWORD=
</pre>
<h3>4. Generate Application Key</h3>
<pre>
php artisan key:generate
</pre>
<h3>5. Migrasi Database</h3>
<pre>
php artisan migrate
</pre>
<h3>6. Symlink Storage (Penting!)</h3>
<pre>
php artisan storage:link
</pre>
<h3>7. Jalankan Aplikasi</h3>
<p>Jika menggunakan <strong>Laragon</strong>, pastikan folder proyek berada di direktori <code>www</code>. Laragon akan otomatis membuat virtual host (misal: <code>http://sistem-keuangan-keluarga.test</code>). Jika tidak menggunakan Laragon, jalankan perintah:</p>
<pre>
php artisan serve
</pre>
<h2>Role Pengguna & Akun Demo</h2>
<p>Sistem ini telah dilengkapi dengan <strong>Database Seeder</strong> untuk memudahkan proses pengujian. Setelah menjalankan <code>php artisan migrate --seed</code>, akun berikut akan tersedia secara otomatis:</p>

<table>
  <tr>
    <th>Role</th>
    <th>Position</th>
    <th>Email</th>
    <th>Password</th>
  </tr>
  <tr>
    <td><strong>Admin</strong></td>
    <td>Husband</td>
    <td>admin@gmail.com</td>
    <td>password123</td>
  </tr>
  <tr>
    <td><strong>User</strong></td>
    <td>Wife</td>
    <td>wif@gmail.com</td>
    <td>password123</td>
  </tr>
  <tr>
    <td><strong>User</strong></td>
    <td>Child</td>
    <td>child1@gmail.com</td>
    <td>password123</td>
  </tr>
  <tr>
    <td><strong>User</strong></td>
    <td>Child</td>
    <td>child2@gmail.com</td>
    <td>password123</td>
  </tr>
</table>

<p><strong>Hak Akses:</strong></p>
<ul>
  <li><strong>Admin (Husband):</strong> Memiliki hak akses penuh, termasuk pengelolaan sistem, persetujuan (approval) pengajuan dana, dan manajemen laporan keuangan.</li>
  <li><strong>User (Wife/Child):</strong> Dapat membuat pengajuan dana baru dan mencatat pengeluaran pribadi yang memerlukan persetujuan dari Admin.</li>
</ul>
<h2>Struktur Folder Penting</h2>
<pre>
app/
├── Livewire/              # Komponen utama (Class-Based)
├── Models/                # Database Eloquent models
├── Policies/              # Aturan hak akses (RBAC)
├── Providers/             # Service Providers
└── Services/              # Business logic (dipisah dari komponen)

resources/
├── views/
└── livewire/ # Blade templates untuk setiap komponen
storage/
└── app/public/evidence/ # Penyimpanan bukti transaksi (Struk/PDF)

routes/
└── web.php # Pengaturan routing aplikasi

</pre>
