<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kedisiplinan - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/sidebar_styles.css") ?>">
    <style>
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
        }
        
        .status-empty {
            color: #6c757d;
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
    <div class="sidebar" id="sidebar"> 
        <div class="sidebar-header">
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo PTA Makassar">
            <h5 class="mt-3"><span>Sistem Manajemen Disiplin Hakim</span></h5>
        </div>
        <!-- Admin Sidebar Menu -->
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("admin/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="<?= base_url("admin/kelola_user") ?>"><i class="fas fa-users"></i> <span>Kelola User</span></a></li>
            <li><a href="<?= base_url("admin/kelola_laporan") ?>"><i class="fas fa-file-alt"></i> <span>Kelola Laporan</span></a></li>
            <li><a href="<?= base_url("admin/rekap_kedisiplinan") ?>" class="active"><i class="fas fa-calendar-check"></i> <span>Rekap Kedisiplinan</span></a></li>
            <li><a href="<?= base_url("admin/notifikasi") ?>"> 
                <i class="fas fa-bell"></i> <span>Notifikasi</span>
                <?php if (session()->get("notif_count") > 0): ?>
                    <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
                <?php endif; ?>
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
                <h5 class="navbar-brand mb-0">Rekap Kedisiplinan User</h5>
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
                    <span>Filter Rekap Kedisiplinan</span>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url("admin/rekap_kedisiplinan") ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">Semua User</option>
                                <?php foreach ($all_users as $user): ?>
                                <option value="<?= $user["id"]; ?>" <?= (isset($filter_user) && $filter_user == $user["id"]) ? "selected" : ""; ?>>
                                    <?= $user["nama_lengkap"]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="satker_id" class="form-label">Satuan Kerja</label>
                            <select class="form-select" id="satker_id" name="satker_id">
                                <option value="">Semua Satker</option>
                                <?php foreach ($satker_list as $satker): ?>
                                <option value="<?= $satker["id"]; ?>" <?= (isset($filter_satker) && $filter_satker == $satker["id"]) ? "selected" : ""; ?>>
                                    <?= $satker["nama_satker"]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php foreach ($daftar_tahun as $tahun): ?>
                                <option value="<?= $tahun; ?>" <?= (isset($tahun_dipilih) && $tahun_dipilih == $tahun) ? "selected" : ""; ?>>
                                    <?= $tahun; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="<?= base_url("admin/rekap_kedisiplinan") ?>" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Tabel Rekapitulasi -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Kedisiplinan User Tahun <?= htmlspecialchars($tahun_dipilih); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableRekap" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Satker</th>
                                    <?php foreach ($nama_bulan as $nama) : ?>
                                        <th class="text-center"><?= $nama; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users_data)) : ?>
                                    <tr>
                                        <td colspan="<?= 3 + count($nama_bulan); ?>" class="text-center">Tidak ada data user yang sesuai dengan filter.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($users_data as $user) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($user["nama_lengkap"]); ?></td>
                                            <td><?= htmlspecialchars($user["nama_satker"] ?? "Tidak ada satker"); ?></td>
                                            <?php foreach ($nama_bulan as $bulan_num => $nama) : ?>
                                                <td class="text-center">
                                                    <?php 
                                                    // Periksa apakah ada data laporan untuk user ini di bulan ini
                                                    if (isset($laporan_data[$user["id"]][$bulan_num])) {
                                                        $status = $laporan_data[$user["id"]][$bulan_num];
                                                        if ($status == "diterima") {
                                                            echo ":heavy_check_mark:"; // Tanda centang hijau
                                                        } elseif ($status == "ditolak") {
                                                            echo "-"; // Kotak kosong
                                                        } else {
                                                            // Status terkirim atau dilihat
                                                            echo "⏳"; // Ikon pending
                                                        }
                                                    } else {
                                                        echo "-"; // Tidak ada laporan
                                                    }
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $("#dataTableRekap").DataTable({
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
            },
            "pageLength": 25
        });
    });
    </script>
</body>
</html>


