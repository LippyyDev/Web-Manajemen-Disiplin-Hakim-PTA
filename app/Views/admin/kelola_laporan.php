<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Laporan - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/sidebar_styles.css") ?>">
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
        <!-- Admin Sidebar Menu -->
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("admin/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="<?= base_url("admin/kelola_user") ?>"><i class="fas fa-users"></i> <span>Kelola User</span></a></li>
            <li>
                <a href="<?= base_url("admin/kelola_laporan") ?>" class="active">
                    <i class="fas fa-file-alt"></i> <span>Kelola Laporan</span>
                    <?php if (session()->get("notif_count") > 0): ?>
                        <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url("admin/rekap_kedisiplinan") ?>"><i class="fas fa-calendar-check"></i> <span>Rekap Kedisiplinan</span></a></li>
            <li><a href="<?= base_url("admin/notifikasi") ?>"> 
                    <i class="fas fa-bell"></i> <span>Notifikasi</span>
             </a></li>
            <li><a href="<?= base_url("admin/profil") ?>"><i class="fas fa-user-cog"></i> <span>Pengaturan Profil</span></a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <h5 class="navbar-brand mb-0">Kelola Laporan</h5>
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
            
            <!-- Filter -->
            <div class="card mb-4">
                <div class="card-header">
                    <span>Filter Laporan</span>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url("admin/kelola_laporan") ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">Pengguna</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">Semua Pengguna</option>
                                <?php 
                                foreach ($users as $user): 
                                ?>
                                <option value="<?= $user["id"]; ?>" <?= (isset($filter_user) && $filter_user == $user["id"]) ? "selected" : ""; ?>>
                                    <?= $user["nama_lengkap"]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="">Semua Bulan</option>
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
                                <option value="">Semua Tahun</option>
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
                            <a href="<?= base_url("admin/kelola_laporan") ?>" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Daftar Laporan -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Laporan</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Laporan</th>
                                    <th>Bulan/Tahun</th>
                                    <th>Pengirim</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Upload</th>
                                    <th>Status</th>
                                    <th>Feedback</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if (!empty($laporan)): 
                                    foreach ($laporan as $row): 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row["nama_laporan"]; ?></td>
                                    <td><?= getBulanIndo($row["bulan"]) . "/" . $row["tahun"]; ?></td>
                                    <td><?= $row["nama_lengkap"]; ?></td>
                                    <td><?= $row["keterangan"]; ?></td>
                                    <td><?= $row["created_at"]; ?></td>
                                    <td>
                                        <span class="status-badge bg-<?= getStatusBadgeColor($row["status"]); ?> text-white">
                                            <?= getStatusIndo($row["status"]); ?>
                                        </span>
                                    </td>
                                    <td><?= $row["feedback"]; ?></td>
                                    <td>
                                        <a href="<?= base_url("admin/kelola_laporan/view/" . $row["id"]) ?>" class="btn btn-info btn-action" target="_blank">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <?php if ($row["status"] != "diterima" && $row["status"] != "ditolak"): ?>
                                            <button type="button" class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#approveModal<?= $row["id"]; ?>">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $row["id"]; ?>">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row["id"]; ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="approveModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="approveModalLabel<?= $row["id"]; ?>">Approve Laporan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= base_url("admin/kelola_laporan/approve") ?>" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="laporan_id" value="<?= $row["id"]; ?>">
                                                            <div class="mb-3">
                                                                <label for="feedback" class="form-label">Feedback (Opsional)</label>
                                                                <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                                                            </div>
                                                            <p>Anda yakin ingin menyetujui laporan ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="approve_laporan" class="btn btn-success">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="rejectModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel<?= $row["id"]; ?>">Tolak Laporan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= base_url("admin/kelola_laporan/reject") ?>" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="laporan_id" value="<?= $row["id"]; ?>">
                                                            <div class="mb-3">
                                                                <label for="feedback" class="form-label">Feedback (Wajib)</label>
                                                                <textarea class="form-control" id="feedback" name="feedback" rows="3" required></textarea>
                                                            </div>
                                                            <p>Anda yakin ingin menolak laporan ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="reject_laporan" class="btn btn-danger">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $row["id"]; ?>">Hapus Laporan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= base_url("admin/kelola_laporan/delete") ?>" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="laporan_id_to_delete" value="<?= $row["id"]; ?>">
                                                            <p>Anda yakin ingin menghapus laporan ini secara permanen?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="delete_laporan_admin" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada laporan ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
            $("#laporanTable").DataTable();

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


