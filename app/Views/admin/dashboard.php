<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Manajemen Disiplin Hakim</title>
    
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
    
    <!-- Page-specific CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
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
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
        .table-responsive {
            overflow-x: auto;
        }
        .table th {
            background-color: #f8f9fa;
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
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo PTA Makassar">
            <h5 class="mt-3"><span>Sistem Manajemen Disiplin Hakim</span></h5>
        </div>
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("admin/dashboard") ?>" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="<?= base_url("admin/kelola_user") ?>"><i class="fas fa-users"></i> <span>Kelola User</span></a></li>
            <li><a href="<?= base_url("admin/kelola_laporan") ?>"><i class="fas fa-file-alt"></i> <span>Kelola Laporan</span></a></li>
            <li><a href="<?= base_url("admin/rekap_kedisiplinan") ?>"><i class="fas fa-calendar-check"></i> <span>Rekap Kedisiplinan</span></a></li>
            <li>
                <a href="<?= base_url("admin/notifikasi") ?>">
                    <i class="fas fa-bell"></i> <span>Notifikasi</span>
                    <?php if ($notif_count > 0): ?>
                        <span class="notification-badge"><?= $notif_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url("admin/profil") ?>"><i class="fas fa-user-cog"></i> <span>Pengaturan Profil</span></a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <h5 class="navbar-brand mb-0">Dashboard Admin</h5>
            </div>
            <div class="user-info">
                <img src="<?= base_url("assets/img/" . session()->get("foto_profil")) ?>" alt="User Profile">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= session()->get("nama_lengkap"); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url("admin/profil") ?>"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
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
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary-light">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $user_count; ?></h3>
                            <p>Total User</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $pegawai_count; ?></h3>
                            <p>Total Pegawai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-danger-light">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $notif_count; ?></h3>
                            <p>Notifikasi Baru</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Laporan -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Status Laporan</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="status-badge bg-warning text-dark">Terkirim</span></td>
                                            <td><?= $status_counts["terkirim"] ?? 0; ?></td>
                                        </tr>
                                        <tr>
                                            <td><span class="status-badge bg-info text-white">Dilihat</span></td>
                                            <td><?= $status_counts["dilihat"] ?? 0; ?></td>
                                        </tr>
                                        <tr>
                                            <td><span class="status-badge bg-success text-white">Diterima</span></td>
                                            <td><?= $status_counts["diterima"] ?? 0; ?></td>
                                        </tr>
                                        <tr>
                                            <td><span class="status-badge bg-danger text-white">Ditolak</span></td>
                                            <td><?= $status_counts["ditolak"] ?? 0; ?></td>
                                        </tr>
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
                            <a href="<?= base_url("admin/kelola_laporan") ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Laporan</th>
                                            <th>Pengirim</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($laporan_terbaru)): ?>
                                            <?php foreach ($laporan_terbaru as $row): ?>
                                                <tr>
                                                    <td><?= $row["nama_laporan"]; ?></td>
                                                    <td><?= $row["nama_lengkap"]; ?></td>
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
        </div>
    </div>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarToggle = document.getElementById("sidebarToggle");
            const mainContent = document.querySelector(".main-content");
            const overlay = document.querySelector(".overlay");

            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", () => {
                    sidebar.classList.toggle("active");
                    mainContent.classList.toggle("active");
                    overlay.classList.toggle("active");
                });
            }

            if (overlay) {
                overlay.addEventListener("click", () => {
                    sidebar.classList.remove("active");
                    mainContent.classList.remove("active");
                    overlay.classList.remove("active");
                });
            }
        });

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
    </script>
</body>
</html>


