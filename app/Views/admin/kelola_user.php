<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Sistem Manajemen Disiplin Hakim</title>
    
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
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo PTA Makassar">
            <h5 class="mt-3"><span>Sistem Manajemen Disiplin Hakim</span></h5>
        </div>
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("admin/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="<?= base_url("admin/kelola_user") ?>" class="active"><i class="fas fa-users"></i> <span>Kelola User</span></a></li>
            <li><a href="<?= base_url("admin/kelola_laporan") ?>"><i class="fas fa-file-alt"></i> <span>Kelola Laporan</span></a></li>
            <li><a href="<?= base_url("admin/rekap_kedisiplinan") ?>"><i class="fas fa-calendar-check"></i> <span>Rekap Kedisiplinan</span></a></li>
            <li>
                <a href="<?= base_url("admin/notifikasi") ?>">
                    <i class="fas fa-bell"></i> <span>Notifikasi</span>
                    <?php if (session()->get("notif_count") > 0): ?>
                        <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
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
                <h5 class="navbar-brand mb-0">Kelola User</h5>
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
            <?php if (session()->getFlashdata("msg")): ?>
                <div class="alert alert-<?= session()->getFlashdata("msg_type") ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata("msg") ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Tambah User -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Tambah User Baru</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                        <i class="fas fa-plus me-1"></i> Tambah User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($users as $row): 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row["username"]; ?></td>
                                    <td><?= $row["nama_lengkap"]; ?></td>
                                    <td><?= $row["email"]; ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row["role"] == "admin" ? "danger" : "primary"; ?>">
                                            <?= ucfirst($row["role"]); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row["id"]; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= base_url("admin/kelola_user/delete/" . $row["id"]) ?>" class="btn btn-danger btn-action" onclick="return confirm("Apakah Anda yakin ingin menghapus user ini?")">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                        
                                        <!-- Modal Edit User -->
                                        <div class="modal fade" id="editUserModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editUserModalLabel<?= $row["id"]; ?>">Edit User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="<?= base_url("admin/kelola_user/update") ?>">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="user_id" value="<?= $row["id"]; ?>">
                                                            <div class="mb-3">
                                                                <label for="username" class="form-label">Username</label>
                                                                <input type="text" class="form-control" id="username" name="username" value="<?= $row["username"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $row["nama_lengkap"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control" id="email" name="email" value="<?= $row["email"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                                                <input type="password" class="form-control" id="password" name="password">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="role" class="form-label">Role</label>
                                                                <select class="form-select" id="role" name="role" required>
                                                                    <option value="admin" <?= $row["role"] == "admin" ? "selected" : ""; ?>>Admin</option>
                                                                    <option value="user" <?= $row["role"] == "user" ? "selected" : ""; ?>>User</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="edit_user" class="btn btn-primary">Simpan Perubahan</button>
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUserModalLabel">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= base_url("admin/kelola_user/add") ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_user" class="btn btn-primary">Tambah User</button>
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
            $("#userTable").DataTable();

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
    </script>
</body>
</html>


