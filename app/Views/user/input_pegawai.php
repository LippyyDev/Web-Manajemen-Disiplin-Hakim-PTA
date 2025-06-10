<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pegawai - Sistem Manajemen Disiplin Hakim</title>
    
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
            <li><a href="<?= base_url("user/input_pegawai") ?>" class="active"><i class="fas fa-user-tie"></i> Input Pegawai</a></li>
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
            <li><a href="<?= base_url("user/profil") ?>"><i class="fas fa-user-cog"></i> Pengaturan Profil</a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div>
                <h5 class="navbar-brand mb-0">Input Pegawai</h5>
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
            
            <!-- Tombol Tambah Satuan Kerja -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Tambah Satuan Kerja</span>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahSatkerModal">
                        <i class="fas fa-plus me-1"></i> Tambah Satuan Kerja
                    </button>
                </div>
            </div>
            
            <!-- Form Tambah Pegawai -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Tambah Pegawai Baru</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPegawaiModal">
                        <i class="fas fa-plus me-1"></i> Tambah Pegawai
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pegawaiTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Pangkat/Golongan</th>
                                    <th>Jabatan</th>
                                    <th>Satuan Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($pegawai as $row): 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row["nama"]; ?></td>
                                    <td><?= $row["nip"]; ?></td>
                                    <td><?= $row["pangkat"] . " " . $row["golongan"]; ?></td>
                                    <td><?= $row["jabatan"]; ?></td>
                                    <td><?= $row["nama_satker"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editPegawaiModal<?= $row["id"]; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= base_url("user/input_pegawai/delete/" . $row["id"]) ?>" class="btn btn-danger btn-action" onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                        
                                        <!-- Modal Edit Pegawai -->
                                        <div class="modal fade" id="editPegawaiModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="editPegawaiModalLabel<?= $row["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPegawaiModalLabel<?= $row["id"]; ?>">Edit Pegawai</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="<?= base_url("user/input_pegawai/update") ?>">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="pegawai_id" value="<?= $row["id"]; ?>">
                                                            <div class="mb-3">
                                                                <label for="nama" class="form-label">Nama Lengkap</label>
                                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $row["nama"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="nip" class="form-label">NIP</label>
                                                                <input type="text" class="form-control" id="nip" name="nip" value="<?= $row["nip"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="pangkat" class="form-label">Pangkat</label>
                                                                <input type="text" class="form-control" id="pangkat" name="pangkat" value="<?= $row["pangkat"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="golongan" class="form-label">Golongan</label>
                                                                <input type="text" class="form-control" id="golongan" name="golongan" value="<?= $row["golongan"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= $row["jabatan"]; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="satker_id" class="form-label">Satuan Kerja</label>
                                                                <select class="form-select" id="satker_id" name="satker_id" required>
                                                                    <?php foreach ($satker_list as $satker): ?>
                                                                        <option value="<?= $satker["id"]; ?>" <?= ($satker["id"] == $row["satker_id"]) ? "selected" : ""; ?>><?= $satker["nama_satker"]; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
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

    <!-- Modal Tambah Satuan Kerja -->
    <div class="modal fade" id="tambahSatkerModal" tabindex="-1" aria-labelledby="tambahSatkerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSatkerModalLabel">Tambah Satuan Kerja Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= base_url("user/input_pegawai/add_satker") ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_satker" class="form-label">Nama Satuan Kerja</label>
                            <input type="text" class="form-control" id="nama_satker" name="nama_satker" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Satuan Kerja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pegawai -->
    <div class="modal fade" id="tambahPegawaiModal" tabindex="-1" aria-labelledby="tambahPegawaiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPegawaiModalLabel">Tambah Pegawai Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= base_url("user/input_pegawai/add") ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="pangkat" class="form-label">Pangkat</label>
                            <input type="text" class="form-control" id="pangkat" name="pangkat" required>
                        </div>
                        <div class="mb-3">
                            <label for="golongan" class="form-label">Golongan</label>
                            <input type="text" class="form-control" id="golongan" name="golongan" required>
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="satker_id" class="form-label">Satuan Kerja</label>
                            <select class="form-select" id="satker_id" name="satker_id" required>
                                <?php foreach ($satker_list as $satker): ?>
                                    <option value="<?= $satker["id"]; ?>"><?= $satker["nama_satker"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pegawai</button>
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
            $("#pegawaiTable").DataTable({
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
            const sidebarToggle = document.getElementById("sidebarToggle");
            const mainContent = document.querySelector(".main-content");
            const overlay = document.querySelector(".overlay");

            function toggleSidebar() {
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    sidebar.classList.toggle("toggled-mobile"); 
                    overlay.classList.toggle("active", sidebar.classList.contains("toggled-mobile"));
                    mainContent.style.marginLeft = sidebar.classList.contains("toggled-mobile") ? "80px" : "80px"; 
                } else {
                    sidebar.classList.toggle("collapsed");
                    mainContent.classList.toggle("sidebar-collapsed");
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", toggleSidebar);
            }
            if (overlay) {
                overlay.addEventListener("click", () => {
                     if (window.innerWidth <= 768) {
                        sidebar.classList.remove("toggled-mobile");
                        overlay.classList.remove("active");
                    } 
                });
            }

            function checkWindowSize() {
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    sidebar.classList.remove("collapsed"); 
                    mainContent.classList.remove("sidebar-collapsed");
                    sidebar.classList.remove("toggled-mobile"); 
                    mainContent.style.marginLeft = "80px";
                    overlay.classList.remove("active");
                } else {
                     sidebar.classList.remove("toggled-mobile"); 
                     mainContent.style.marginLeft = sidebar.classList.contains("collapsed") ? "80px" : "200px";
                     overlay.classList.remove("active");
                }
            }

            window.addEventListener("resize", checkWindowSize);
            checkWindowSize(); // Initial check
        });
    </script>
</body>
</html>