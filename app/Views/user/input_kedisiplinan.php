<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Kedisiplinan - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/sidebar_styles.css") ?>">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Inline Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
            color: #fff;
            position: fixed;
            width: 200px;
        }
        .sidebar-header {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-header img {
            max-width: 80px;
            height: auto;
        }
        .sidebar-header h5 {
            font-size: 1rem;
        }
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        .sidebar-menu li {
            margin-bottom: 3px;
        }
        .sidebar-menu li a {
            display: block;
            padding: 10px 15px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background-color: #34495e;
            color: #fff;
        }
        .sidebar-menu li a i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
            font-size: 0.9rem;
        }
        .main-content {
            margin-left: 200px;
            padding: 20px;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .user-info .dropdown-toggle {
            color: #2c3e50;
            text-decoration: none;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f1f1;
            font-weight: bold;
            padding: 15px 20px;
        }
        .card-body {
            padding: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
        }
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #e74c3c;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo PTA Makassar">
            <h5 class="mt-3">Sistem Manajemen Disiplin Hakim</h5>
        </div>
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("user/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= base_url("user/input_pegawai") ?>"><i class="fas fa-user-tie"></i> Input Pegawai</a></li>
            <li><a href="<?= base_url("user/input_kedisiplinan") ?>" class="active"><i class="fas fa-clipboard-list"></i> Input Kedisiplinan</a></li>
            <li><a href="<?= base_url("user/input_tanda_tangan") ?>"><i class="fas fa-signature"></i> Input Tanda Tangan</a></li>
            <li><a href="<?= base_url("user/rekap_laporan") ?>"><i class="fas fa-file-alt"></i> Rekap Laporan</a></li>
            <li><a href="<?= base_url("user/rekap_bulanan") ?>"><i class="fas fa-calendar-check"></i> Rekap Kedisiplinan<br>Bulanan</a></li>
            <li><a href="<?= base_url("user/upload_file") ?>"><i class="fas fa-upload"></i> Upload File</a></li>
            <li>
                <a href="<?= base_url("user/notifikasi") ?>">
                    <i class="fas fa-bell"></i> Notifikasi
                    <?php if (session()->get("notif_count") > 0): ?>
                        <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url("user/profil") ?>"><i class="fas fa-user-cog"></i> Pengaturan Profil</a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div>
                <h5 class="navbar-brand mb-0">Input Kedisiplinan</h5>
            </div>
            <div class="user-info">
                <img src="<?= base_url("assets/img/" . session()->get("foto_profil")) ?>" alt="User Profile">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= session()->get("nama_lengkap"); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url("user/profil") ?>"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="container-fluid">
            <!-- Alert -->
            <?php if (session()->getFlashdata("msg")): ?>
                <div class="alert alert-<?= (session()->getFlashdata("msg_type")) ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata("msg") ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Filter -->
            <div class="card mb-4">
                <div class="card-header">
                    <span>Filter Data Kedisiplinan</span>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url("user/input_kedisiplinan") ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="satker" class="form-label">Satuan Kerja</label>
                            <select class="form-select" id="satker" name="satker">
                                <option value="">Semua Satker</option>
                                <?php foreach ($satker_list as $satker): ?>
                                <option value="<?= $satker["id"]; ?>" <?= (isset($filter_satker) && $filter_satker == $satker["id"]) ? "selected" : ""; ?>>
                                    <?= $satker["nama_satker"]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i; ?>" <?= (isset($filter_bulan) && $filter_bulan == $i) ? "selected" : ""; ?>>
                                    <?= getBulanIndo($i); ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php foreach ($tahun_tersedia as $tahun): ?>
                                <option value="<?= $tahun; ?>" <?= (isset($filter_tahun) && $filter_tahun == $tahun) ? "selected" : ""; ?>>
                                    <?= $tahun; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Tambah Kedisiplinan -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Input Data Kedisiplinan</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKedisiplinanModal">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="kedisiplinanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>NIP</th>
                                    <th>Bulan/Tahun</th>
                                    <th>Terlambat</th>
                                    <th>Tidak Absen Masuk</th>
                                    <th>Pulang Awal</th>
                                    <th>Tidak Absen Pulang</th>
                                    <th>Keluar Tidak Izin</th>
                                    <th>Tidak Masuk Tanpa Ket</th>
                                    <th>Tidak Masuk Sakit</th>
                                    <th>Tidak Masuk Kerja</th>
                                    <th>Bentuk Pembinaan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($kedisiplinan_data as $row): 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row["nama"]; ?></td>
                                    <td><?= $row["nip"]; ?></td>
                                    <td><?= getBulanIndo($row["bulan"]) . " " . $row["tahun"]; ?></td>
                                    <td><?= $row["terlambat"]; ?></td>
                                    <td><?= $row["tidak_absen_masuk"]; ?></td>
                                    <td><?= $row["pulang_awal"]; ?></td>
                                    <td><?= $row["tidak_absen_pulang"]; ?></td>
                                    <td><?= $row["keluar_tidak_izin"]; ?></td>
                                    <td><?= $row["tidak_masuk_tanpa_ket"]; ?></td>
                                    <td><?= $row["tidak_masuk_sakit"]; ?></td>
                                    <td><?= $row["tidak_masuk_kerja"]; ?></td>
                                    <td><?= $row["bentuk_pembinaan"]; ?></td>
                                    <td><?= $row["keterangan"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editKedisiplinanModal<?= $row["id"]; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= base_url("user/input_kedisiplinan/delete/" . $row["id"]) ?>" class="btn btn-danger btn-action" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>

                                        <!-- Modal Edit Kedisiplinan -->
                                        <div class="modal fade" id="editKedisiplinanModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="editKedisiplinanModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editKedisiplinanModalLabel<?= $row["id"]; ?>">Edit Data Kedisiplinan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="<?= base_url("user/input_kedisiplinan/update") ?>">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="kedisiplinan_id" value="<?= $row["id"]; ?>">
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <label for="pegawai_id" class="form-label">Nama Pegawai</label>
                                                                    <select class="form-select" id="pegawai_id" name="pegawai_id" disabled>
                                                                        <?php foreach ($pegawai_list as $pegawai): ?>
                                                                            <option value="<?= $pegawai["id"]; ?>" <?= ($pegawai["id"] == $row["pegawai_id"]) ? "selected" : ""; ?>><?= $pegawai["nama"]; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="bulan" class="form-label">Bulan</label>
                                                                    <select class="form-select" id="bulan" name="bulan" disabled>
                                                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                                                            <option value="<?= $i; ?>" <?= ($i == $row["bulan"]) ? "selected" : ""; ?>><?= getBulanIndo($i); ?></option>
                                                                        <?php endfor; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tahun" class="form-label">Tahun</label>
                                                                    <input type="text" class="form-control" id="tahun" name="tahun" value="<?= $row["tahun"]; ?>" disabled>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="terlambat" class="form-label">Terlambat</label>
                                                                    <input type="number" class="form-control" id="terlambat" name="terlambat" value="<?= $row["terlambat"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tidak_absen_masuk" class="form-label">Tidak Absen Masuk</label>
                                                                    <input type="number" class="form-control" id="tidak_absen_masuk" name="tidak_absen_masuk" value="<?= $row["tidak_absen_masuk"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="pulang_awal" class="form-label">Pulang Awal</label>
                                                                    <input type="number" class="form-control" id="pulang_awal" name="pulang_awal" value="<?= $row["pulang_awal"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tidak_absen_pulang" class="form-label">Tidak Absen Pulang</label>
                                                                    <input type="number" class="form-control" id="tidak_absen_pulang" name="tidak_absen_pulang" value="<?= $row["tidak_absen_pulang"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="keluar_tidak_izin" class="form-label">Keluar Tidak Izin</label>
                                                                    <input type="number" class="form-control" id="keluar_tidak_izin" name="keluar_tidak_izin" value="<?= $row["keluar_tidak_izin"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tidak_masuk_tanpa_ket" class="form-label">Tidak Masuk Tanpa Ket</label>
                                                                    <input type="number" class="form-control" id="tidak_masuk_tanpa_ket" name="tidak_masuk_tanpa_ket" value="<?= $row["tidak_masuk_tanpa_ket"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tidak_masuk_sakit" class="form-label">Tidak Masuk Sakit</label>
                                                                    <input type="number" class="form-control" id="tidak_masuk_sakit" name="tidak_masuk_sakit" value="<?= $row["tidak_masuk_sakit"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="tidak_masuk_kerja" class="form-label">Tidak Masuk Kerja</label>
                                                                    <input type="number" class="form-control" id="tidak_masuk_kerja" name="tidak_masuk_kerja" value="<?= $row["tidak_masuk_kerja"]; ?>" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="bentuk_pembinaan" class="form-label">Bentuk Pembinaan</label>
                                                                    <textarea class="form-control" id="bentuk_pembinaan" name="bentuk_pembinaan" rows="3"><?= $row["bentuk_pembinaan"]; ?></textarea>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="keterangan" class="form-label">Keterangan</label>
                                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= $row["keterangan"]; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kedisiplinan -->
    <div class="modal fade" id="tambahKedisiplinanModal" tabindex="-1" aria-labelledby="tambahKedisiplinanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKedisiplinanModalLabel">Tambah Data Kedisiplinan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= base_url("user/input_kedisiplinan/add") ?>">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="pegawai_id" class="form-label">Nama Pegawai</label>
                                <select class="form-select" id="pegawai_id" name="pegawai_id" required>
                                    <?php foreach ($pegawai_list as $pegawai): ?>
                                        <option value="<?= $pegawai["id"]; ?>"><?= $pegawai["nama"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select class="form-select" id="bulan" name="bulan" required>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i; ?>" <?= ($i == date("n")) ? "selected" : ""; ?>><?= getBulanIndo($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" value="<?= date("Y"); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="terlambat" class="form-label">Terlambat</label>
                                <input type="number" class="form-control" id="terlambat" name="terlambat" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tidak_absen_masuk" class="form-label">Tidak Absen Masuk</label>
                                <input type="number" class="form-control" id="tidak_absen_masuk" name="tidak_absen_masuk" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="pulang_awal" class="form-label">Pulang Awal</label>
                                <input type="number" class="form-control" id="pulang_awal" name="pulang_awal" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tidak_absen_pulang" class="form-label">Tidak Absen Pulang</label>
                                <input type="number" class="form-control" id="tidak_absen_pulang" name="tidak_absen_pulang" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="keluar_tidak_izin" class="form-label">Keluar Tidak Izin</label>
                                <input type="number" class="form-control" id="keluar_tidak_izin" name="keluar_tidak_izin" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tidak_masuk_tanpa_ket" class="form-label">Tidak Masuk Tanpa Ket</label>
                                <input type="number" class="form-control" id="tidak_masuk_tanpa_ket" name="tidak_masuk_tanpa_ket" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tidak_masuk_sakit" class="form-label">Tidak Masuk Sakit</label>
                                <input type="number" class="form-control" id="tidak_masuk_sakit" name="tidak_masuk_sakit" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tidak_masuk_kerja" class="form-label">Tidak Masuk Kerja</label>
                                <input type="number" class="form-control" id="tidak_masuk_kerja" name="tidak_masuk_kerja" value="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="bentuk_pembinaan" class="form-label">Bentuk Pembinaan</label>
                                <textarea class="form-control" id="bentuk_pembinaan" name="bentuk_pembinaan" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $("#kedisiplinanTable").DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            const sidebar = document.getElementById("sidebar");
            const mainContent = document.querySelector(".main-content");

            function checkWindowSize() {
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    sidebar.classList.remove("collapsed"); 
                    mainContent.classList.remove("sidebar-collapsed");
                    sidebar.classList.remove("toggled-mobile"); 
                    mainContent.style.marginLeft = "80px";
                } else {
                    sidebar.classList.remove("toggled-mobile"); 
                    mainContent.style.marginLeft = sidebar.classList.contains("collapsed") ? "80px" : "200px";
                }
            }

            window.addEventListener("resize", checkWindowSize);
            checkWindowSize(); // Initial check
        });
    </script>
</body>
</html>