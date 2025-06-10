<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan - Sistem Manajemen Disiplin Hakim</title>
    
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
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h4 {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-header p {
            margin-bottom: 5px;
        }
        .report-footer {
            margin-top: 30px;
            text-align: right;
        }
        .report-footer p {
            margin-bottom: 5px;
        }
        .report-signature {
            margin-top: 80px; /* Increased space for signature */
        }
        /* Custom style untuk tabel dengan header kompleks */
        .complex-table th {
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
        }
        .complex-table td {
            vertical-align: middle;
        }
        /* Batasi lebar kolom "BENTUK PEMBINAAN" dan "KETERANGAN" */
        .complex-table th:nth-child(14),
        .complex-table td:nth-child(14) {
            max-width: 150px; /* Batasi lebar maksimum */
            word-wrap: break-word; /* Membungkus teks */
            overflow: hidden; /* Sembunyikan kelebihan teks */
            text-overflow: ellipsis; /* Tambahkan elipsis (...) jika terlalu panjang */
            white-space: normal; /* Izinkan teks membungkus */
        }
        .complex-table th:nth-child(15),
        .complex-table td:nth-child(15) {
            max-width: 150px; /* Batasi lebar maksimum */
            word-wrap: break-word; /* Membungkus teks */
            overflow: hidden; /* Sembunyikan kelebihan teks */
            text-overflow: ellipsis; /* Tambahkan elipsis (...) jika terlalu panjang */
            white-space: normal; /* Izinkan teks membungkus */
        }
        /* Distribusi lebar kolom yang lebih merata */
        .complex-table th,
        .complex-table td {
            min-width: 80px; /* Lebar minimum untuk kolom lain */
        }
        .keterangan-section {
            margin-top: 30px;
            font-size: 0.9em;
        }
        .keterangan-section h6 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .keterangan-section ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
        }
        .keterangan-section ul li {
            width: 50%;
            margin-bottom: 5px;
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
            .keterangan-section ul li {
                width: 100%;
            }
            .complex-table th:nth-child(14),
            .complex-table td:nth-child(14),
            .complex-table th:nth-child(15),
            .complex-table td:nth-child(15) {
                max-width: 100px; /* Kurangi lebar di layar kecil */
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
            <li><a href="<?= base_url("user/input_kedisiplinan") ?>"><i class="fas fa-clipboard-list"></i> Input Kedisiplinan</a></li>
            <li><a href="<?= base_url("user/input_tanda_tangan") ?>"><i class="fas fa-signature"></i> Input Tanda Tangan</a></li>
            <li><a href="<?= base_url("user/rekap_laporan") ?>" class="active"><i class="fas fa-file-alt"></i> Rekap Laporan</a></li>
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
                <h5 class="navbar-brand mb-0">Rekap Laporan</h5>
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
                <div class="alert alert-<?= session()->getFlashdata("msg_type") ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata("msg") ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Filter -->
            <div class="card mb-4">
                <div class="card-header">
                    <span>Filter Rekap Laporan</span>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url("user/rekap_laporan") ?>" class="row g-3" id="filterForm">
                        <div class="col-md-4">
                            <label for="satker" class="form-label">Satuan Kerja</label>
                            <select class="form-select" id="satker" name="satker">
                                <option value="">Semua Satker</option>
                                <?php foreach ($satker_list as $satker): ?>
                                <option value="<?= $satker["id"]; ?>" <?= $filter_satker == $satker["id"] ? "selected" : ""; ?>>
                                    <?= $satker["nama_satker"]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i; ?>" <?= $filter_bulan == $i ? "selected" : ""; ?>>
                                    <?= getBulanIndo($i); ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php foreach ($tahun_tersedia as $tahun): ?>
                                <option value="<?= $tahun; ?>" <?= $filter_tahun == $tahun ? "selected" : ""; ?>>
                                    <?= $tahun; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="<?= base_url("user/rekap_laporan") ?>" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                    <div class="mt-3">
                        <form method="POST" action="<?= base_url("user/rekap_laporan/export_pdf") ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="satker" value="<?= $filter_satker ?>">
                            <input type="hidden" name="bulan" value="<?= $filter_bulan ?>">
                            <input type="hidden" name="tahun" value="<?= $filter_tahun ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </form>
                        <form method="POST" action="<?= base_url("user/rekap_laporan/export_excel") ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="satker" value="<?= $filter_satker ?>">
                            <input type="hidden" name="bulan" value="<?= $filter_bulan ?>">
                            <input type="hidden" name="tahun" value="<?= $filter_tahun ?>">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Rekap Laporan -->
            <div class="card">
                <div class="card-header">
                    <span>Rekap Laporan Disiplin Hakim</span>
                </div>
                <div class="card-body">
                    <!-- Header Laporan -->
                    <div class="report-header">
                        <h4>LAPORAN DISIPLIN HAKIM</h4>
                        <h5>YANG TIDAK MEMATUHI KETENTUAN JAM KERJA SESUAI DENGAN PERMA NO 7 TAHUN 2016</h5>
                        <p>BULAN : <?= strtoupper(getBulanIndo($filter_bulan)) . ' ' . $filter_tahun; ?></p>
                        <?php if (!empty($filter_satker)): 
                            $satker_name = '';
                            foreach ($satker_list as $satker) {
                                if ($satker['id'] == $filter_satker) {
                                    $satker_name = $satker['nama_satker'];
                                    break;
                                }
                            }
                        ?>
                        <p>SATKER : <?= strtoupper($satker_name); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Tabel Rekap -->
                    <div class="table-responsive">
                        <table id="rekapTable" class="table table-bordered complex-table">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2">NO</th>
                                    <th class="text-center" rowspan="2">NAMA/NIP</th>
                                    <th class="text-center" rowspan="2">PANGKAT/GOL. RUANG</th>
                                    <th class="text-center" rowspan="2">JABATAN</th>
                                    <th class="text-center" rowspan="2">SATUAN KERJA</th>
                                    <th class="text-center" colspan="8">URAIAN AKUMULASI TIDAK DIPATUHKANNYA JAM KERJA DALAM 1 BULAN</th>
                                    <th class="text-center" rowspan="2">BENTUK PEMBINAAN</th>
                                    <th class="text-center" rowspan="2">KETERANGAN</th>
                                </tr>
                                <tr>
                                    <th class="text-center">t</th>
                                    <th class="text-center">tam</th>
                                    <th class="text-center">pa</th>
                                    <th class="text-center">tap</th>
                                    <th class="text-center">kti</th>
                                    <th class="text-center">tk</th>
                                    <th class="text-center">tms</th>
                                    <th class="text-center">tmk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($kedisiplinan_data as $row): 
                                    $total = $row["terlambat"] + $row["tidak_absen_masuk"] + $row["pulang_awal"] + $row["tidak_absen_pulang"] + $row["keluar_tidak_izin"] + $row["tidak_masuk_tanpa_ket"] + $row["tidak_masuk_sakit"] + $row["tidak_masuk_kerja"];
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row["nama"]; ?><br><?= $row["nip"]; ?></td>
                                    <td><?= $row["pangkat"]; ?><br><?= $row["golongan"]; ?></td>
                                    <td><?= $row["jabatan"]; ?></td>
                                    <td><?= $row["nama_satker"]; ?></td>
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
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Keterangan Singkatan -->
                    <div class="keterangan-section">
                        <h6>KETERANGAN :</h6>
                        <ul>
                            <li>t = TERLAMBAT</li>
                            <li>kti = KELUAR KANTOR TIDAK IZIN ATASAN</li>
                            <li>tam = TIDAK ABSEN MASUK</li>
                            <li>tk = TIDAK MASUK TANPA KETERANGAN</li>
                            <li>pa = PULANG AWAL</li>
                            <li>tms = TIDAK MASUK KARENA SAKIT TANPA MENGAJUKAN CUTI SAKIT</li>
                            <li>tap = TIDAK ABSEN PULANG</li>
                            <li>tmk = TIDAK MASUK KERJA</li>
                        </ul>
                    </div>

                    <!-- Footer Laporan -->
                    <div class="report-footer">
                        <?php if (!empty($tanda_tangan)): ?>
                            <p><?= $tanda_tangan["lokasi"]; ?>, <?= tanggalIndo($tanda_tangan["tanggal"]); ?></p>
                            <p><?= $tanda_tangan["nama_jabatan"]; ?></p>
                            <div class="report-signature">
                                <p><b><?= $tanda_tangan["nama_penandatangan"]; ?></b></p>
                                <p>NIP. <?= $tanda_tangan["nip_penandatangan"]; ?></p>
                            </div>
                        <?php else: ?>
                            <p>Tanda tangan belum tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#rekapTable").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false, // Nonaktifkan auto-width untuk kontrol manual
                "columnDefs": [
                    { "width": "50px", "targets": 0 }, // NO
                    { "width": "150px", "targets": 1 }, // NAMA/NIP
                    { "width": "120px", "targets": 2 }, // PANGKAT/GOL. RUANG
                    { "width": "120px", "targets": 3 }, // JABATAN
                    { "width": "150px", "targets": 4 }, // SATUAN KERJA
                    { "width": "50px", "targets": [5, 6, 7, 8, 9, 10, 11, 12] }, // Kolom URAIAN (t, tam, pa, tap, kti, tk, tms, tmk)
                    { "width": "120px", "targets": 13 }, // BENTUK PEMBINAAN
                    { "width": "120px", "targets": 14 } // KETERANGAN
                ]
            });
        });
    </script>
</body>
</html>