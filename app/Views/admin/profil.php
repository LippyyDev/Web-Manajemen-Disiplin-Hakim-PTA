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
        body { background-color: #f8f9fa; }
        .main-content { margin-left: 250px; padding: 20px; }
        .navbar { background-color: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 15px 20px; margin-bottom: 20px; }
        .navbar-brand { font-weight: bold; color: #2c3e50; }
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .user-info .dropdown-toggle { color: #2c3e50; text-decoration: none; }
        .card { border: none; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #f1f1f1; font-weight: bold; padding: 15px 20px; }
        .card-body { padding: 20px; }
        .profile-image { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; }
        @media (max-width: 768px) { .sidebar { width: 100%; position: relative; min-height: auto; margin-bottom: 20px; } .main-content { margin-left: 0; } }
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
            <li><a href="<?= base_url("user/rekap_bulanan") ?>"><i class="fas fa-calendar-check"></i> Rekap Kedisiplinan Bulanan</a></li>
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
            <?php if (session()->getFlashdata("msg")): ?>
                <div class="alert alert-<?= session()->getFlashdata("msg_type") ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata("msg") ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Profil</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?= base_url("assets/img/" . (empty($user_data['foto_profil']) ? 'default.png' : $user_data['foto_profil'])) ?>" alt="Profile Image" class="profile-image mb-3">
                            <form action="<?= base_url('user/profil/update_foto') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="foto_profil" class="form-label">Ubah Foto Profil</label>
                                    <input type="file" class="form-control" id="foto_profil" name="foto_profil">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maks: 2MB</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Foto</button>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profil</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Password</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="password" role="tabpanel">
                                    <form action="<?= base_url('user/profil/update') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="tab" value="password">
                                        <div class="mb-3">
                                            <label for="password_lama" class="form-label">Password Lama</label>
                                            <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_baru" class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ubah Password</button>
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