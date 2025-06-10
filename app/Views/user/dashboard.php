<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
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
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .stat-info p {
            margin-bottom: 0;
            color: #7f8c8d;
        }
        .bg-primary-light {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        .bg-success-light {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        .bg-warning-light {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }
        .bg-danger-light {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
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
        .table-responsive {
            overflow-x: auto;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
            <li><a href="<?= base_url("user/dashboard") ?>" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= base_url("user/input_pegawai") ?>"><i class="fas fa-user-tie"></i> Input Pegawai</a></li>
            <li><a href="<?= base_url("user/input_kedisiplinan") ?>"><i class="fas fa-clipboard-list"></i> Input Kedisiplinan</a></li>
            <li><a href="<?= base_url("user/input_tanda_tangan") ?>"><i class="fas fa-signature"></i> Input Tanda Tangan</a></li>
            <li><a href="<?= base_url("user/rekap_laporan") ?>"><i class="fas fa-file-alt"></i> Rekap Laporan</a></li>
            <li><a href="<?= base_url("user/rekap_bulanan") ?>"><i class="fas fa-calendar-check"></i> Rekap Kedisiplinan Bulanan</a></li>
            <li><a href="<?= base_url("user/upload_file") ?>"><i class="fas fa-upload"></i> Upload File</a></li>
            <li>
                <a href="<?= base_url("user/notifikasi") ?>">
                    <i class="fas fa-bell"></i> Notifikasi
                    <?php if ($notif_count > 0): ?>
                        <span class="notification-badge"><?= $notif_count; ?></span>
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
                <h5 class="navbar-brand mb-0">Dashboard User</h5>
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
            <?php // showAlert(); // Will implement later if needed ?>
            
            <!-- Statistics -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary-light">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $pegawai_count; ?></h3>
                            <p>Total Pegawai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $kedisiplinan_count; ?></h3>
                            <p>Total Data Kedisiplinan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning-light">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $laporan_count; ?></h3>
                            <p>Total Laporan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Satuan Kerja -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Satuan Kerja</span>
                            <a href="<?= base_url("user/input_pegawai") ?>" class="btn btn-sm btn-primary">Input Pegawai</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Satker</th>
                                            <th>Jumlah Pegawai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        foreach ($satker_list as $satker): 
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $satker["nama_satker"]; ?></td>
                                            <td><?= $satker["jumlah_pegawai"]; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Laporan Terbaru -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Laporan Terbaru</span>
                            <a href="<?= base_url("user/upload_file") ?>" class="btn btn-sm btn-primary">Upload File</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Laporan</th>
                                            <th>Bulan/Tahun</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($laporan_terbaru)): ?>
                                            <?php foreach ($laporan_terbaru as $row): ?>
                                                <tr>
                                                    <td><?= $row["nama_laporan"]; ?></td>
                                                    <td><?= getBulanIndo($row["bulan"]) . " " . $row["tahun"]; ?></td>
                                                    <td>
                                                        <span class="status-badge bg-<?= getStatusBadgeColor($row["status"]); ?> text-white">
                                                            <?= getStatusIndo($row["status"]); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada laporan</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Menu Cepat -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span>Menu Cepat</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/input_pegawai") ?>" class="btn btn-outline-primary w-100"><i class="fas fa-user-tie me-2"></i> Input Pegawai</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/input_kedisiplinan") ?>" class="btn btn-outline-success w-100"><i class="fas fa-clipboard-list me-2"></i> Input Kedisiplinan</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/input_tanda_tangan") ?>" class="btn btn-outline-info w-100"><i class="fas fa-signature me-2"></i> Input Tanda Tangan</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/rekap_laporan") ?>" class="btn btn-outline-warning w-100"><i class="fas fa-file-alt me-2"></i> Rekap Laporan</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/rekap_bulanan") ?>" class="btn btn-outline-danger w-100"><i class="fas fa-calendar-check me-2"></i> Rekap Bulanan</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/upload_file") ?>" class="btn btn-outline-secondary w-100"><i class="fas fa-upload me-2"></i> Upload File</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/notifikasi") ?>" class="btn btn-outline-dark w-100"><i class="fas fa-bell me-2"></i> Notifikasi</a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url("user/profil") ?>" class="btn btn-outline-info w-100"><i class="fas fa-user-cog me-2"></i> Pengaturan Profil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getStatusBadgeColor(status) {
            switch (status) {
                case "terkirim":
                    return "warning";
                case "dilihat":
                    return "info";
                case "diterima":
                    return "success";
                case "ditolak":
                    return "danger";
                default:
                    return "secondary";
            }
        }

        function getStatusIndo(status) {
            switch (status) {
                case "terkirim":
                    return "Terkirim";
                case "dilihat":
                    return "Dilihat";
                case "diterima":
                    return "Diterima";
                case "ditolak":
                    return "Ditolak";
                default:
                    return "Tidak Diketahui";
            }
        }

        function getBulanIndo(bulan) {
            const namaBulan = [
                "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            return namaBulan[bulan];
        }
    </script>
</body>
</html>