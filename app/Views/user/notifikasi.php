<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/sidebar_styles.css") ?>">
    
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
        .notification-item {
            transition: all 0.3s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #e8f4fd;
            font-weight: bold;
        }
        .notification-item .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #343a40;
        }
        .notification-item.unread .notification-title {
            font-weight: 900;
        }
        .notification-item .notification-time {
            font-size: 12px;
            color: #6c757d;
        }
        .notification-item .notification-message {
            margin-bottom: 5px;
            color: #495057;
            font-weight: normal;
        }
        .notification-empty {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }
        .list-group-item-action {
            cursor: pointer;
        }
        .list-group-item {
            border-left: 0;
            border-right: 0;
            border-radius: 0;
        }
        .list-group-item:first-child {
            border-top: 0;
        }
        .list-group-item:last-child {
            border-bottom: 0;
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
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo Instansi">
            <h5 class="mt-3">Sistem Manajemen Disiplin Hakim</h5>
        </div>
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("user/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= base_url("user/input_pegawai") ?>"><i class="fas fa-user-tie"></i> Input Pegawai</a></li>
            <li><a href="<?= base_url("user/input_kedisiplinan") ?>"><i class="fas fa-clipboard-list"></i> Input Kedisiplinan</a></li>
            <li><a href="<?= base_url("user/input_tanda_tangan") ?>"><i class="fas fa-signature"></i> Input Tanda Tangan</a></li>
            <li><a href="<?= base_url("user/rekap_laporan") ?>"><i class="fas fa-file-alt"></i> Rekap Laporan</a></li>
             <li><a href="<?= base_url("user/rekap_bulanan") ?>"><i class="fas fa-calendar-check"></i> Rekap Kedisiplinan<br>Bulanan</a></li>
            <li><a href="<?= base_url("user/upload_file") ?>"><i class="fas fa-upload"></i> Upload File</a></li>
            <li>
                <a href="<?= base_url("user/notifikasi") ?>" class="active">
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
                <h5 class="navbar-brand mb-0">Notifikasi</h5>
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
            
            <!-- Notifikasi -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Notifikasi (Otomatis terhapus setelah 3 hari)</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($notifikasi_list)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifikasi_list as $row): 
                                $link = base_url("user/dashboard"); // Default link
                                if (($row["tipe"] == "status" || $row["tipe"] == "feedback" || $row["tipe"] == "laporan") && !empty($row["id_referensi"])) {
                                    $link = base_url("user/upload_file") . "#file-" . $row["id_referensi"];
                                } elseif ($row["tipe"] == "sistem") {
                                    $link = base_url("user/dashboard");
                                }
                            ?>
                                <a href="<?= $link; ?>" class="list-group-item list-group-item-action notification-item <?= $row["is_read"] == 0 ? "unread" : ""; ?>" data-notif-id="<?= $row["id"]; ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 notification-title"><?= htmlspecialchars($row["judul"]); ?></h5>
                                        <small class="notification-time"><i class="far fa-clock me-1"></i> <?= timeAgo($row["created_at"]); ?></small>
                                    </div>
                                    <p class="mb-1 notification-message"><?= htmlspecialchars($row["pesan"]); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="notification-empty">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <h5>Tidak ada notifikasi</h5>
                            <p>Anda belum memiliki notifikasi apapun saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>