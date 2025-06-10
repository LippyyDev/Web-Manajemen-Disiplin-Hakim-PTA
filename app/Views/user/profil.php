<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .nav-tabs .nav-link {
            color: #2c3e50;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #3498db;
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
                <a href="<?= base_url("user/notifikasi") ?>">
                    <i class="fas fa-bell"></i> Notifikasi
                    <?php if (session()->get("notif_count") > 0): ?>
                        <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url("user/profil") ?>" class="active"><i class="fas fa-user-cog"></i> Pengaturan Profil</a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div>
                <h5 class="navbar-brand mb-0">Pengaturan Profil</h5>
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
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url("assets/img/" . $user_data["foto_profil"]) ?>" alt="Profile Image" class="profile-image">
                            <h5><?= $user_data["nama_lengkap"]; ?></h5>
                            <p class="text-muted"><?= $user_data["email"]; ?></p>
                            <p class="text-muted">
                                <span class="badge bg-primary">
                                    <?= $user_data["role"] == "admin" ? "Administrator" : "User"; ?>
                                </span>
                            </p>
                            <form method="POST" action="<?= base_url("user/profil/update_foto") ?>" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="foto_profil" class="form-label">Ubah Foto Profil</label>
                                    <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, GIF. Maks: 2MB</div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Foto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                        <i class="fas fa-user me-1"></i> Profil
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                                        <i class="fas fa-key me-1"></i> Password
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="profileTabContent">
                                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form method="POST" action="<?= base_url("user/profil/update") ?>">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $user_data["nama_lengkap"]; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $user_data["email"]; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= $user_data["username"]; ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form method="POST" action="<?= base_url("user/profil/update") ?>">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label for="password_lama" class="form-label">Password Lama</label>
                                            <input type="password" class="form-control" id="password_lama" name="password_lama">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_baru" class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" id="password_baru" name="password_baru">
                                        </div>
                                        <div class="mb-3">
                                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password">
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Ubah Password
                                        </button>
                                    </form>
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
</body>
</html>