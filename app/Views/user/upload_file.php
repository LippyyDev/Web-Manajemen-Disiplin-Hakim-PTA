<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - Sistem Manajemen Disiplin Hakim</title>
    
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .file-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .dropzone {
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 5px;
            cursor: pointer;
        }
        .dropzone.hover {
            background-color: #e9ecef;
        }
        .file-list {
            margin-top: 15px;
        }
        .file-list .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .file-list .file-item:last-child {
            border-bottom: none;
        }
        .file-list .file-item .file-name {
            flex-grow: 1;
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
                margin-bottom: 20px;
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
            <li><a href="<?= base_url("user/upload_file") ?>" class="active"><i class="fas fa-upload"></i> Upload File</a></li>
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
                <h5 class="navbar-brand mb-0">Upload File Laporan</h5>
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
                    <h6 class="m-0 font-weight-bold text-primary">Form Upload Laporan</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url("user/upload_file/add") ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_laporan" class="form-label">Nama Laporan</label>
                            <input type="text" class="form-control" id="nama_laporan" name="nama_laporan" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select class="form-select" id="bulan" name="bulan" required>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i; ?>" <?= (date("n") == $i) ? "selected" : ""; ?>><?= getBulanIndo($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select class="form-select" id="tahun" name="tahun" required>
                                    <?php for ($i = date("Y"); $i >= 2000; $i--): ?>
                                        <option value="<?= $i; ?>" <?= (date("Y") == $i) ? "selected" : ""; ?>><?= $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="files" class="form-label">Pilih File (Max 5MB per file, Max 5 file)</label>
                            <input type="file" class="form-control" id="files" name="files[]" multiple required>
                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX.</small>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload Laporan</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Laporan yang Diupload</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableLaporan" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Laporan</th>
                                    <th>Bulan/Tahun</th>
                                    <th>File</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Feedback Admin</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($laporan_data)): ?>
                                    <?php $no = 1; foreach ($laporan_data as $laporan): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($laporan["nama_laporan"]); ?></td>
                                            <td><?= getBulanIndo($laporan["bulan"]) . " " . $laporan["tahun"]; ?></td>
                                            <td>
                                                <?php
                                                $file_extension = pathinfo($laporan['file_path'], PATHINFO_EXTENSION);
                                                $file_name = $laporan['nama_laporan']; // Menggunakan nama_laporan untuk teks yang lebih deskriptif
                                                if ($file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "png" || $file_extension == "gif") {
                                                    echo "<a href='" . base_url("user/getFile/" . urlencode($laporan['file_path'])) . "' target='_blank' class='file-link'><i class='fas fa-image file-icon'></i> " . $file_name . "</a>";
                                                } else if ($file_extension == "pdf") {
                                                    echo "<a href='" . base_url("user/getFile/" . urlencode($laporan['file_path'])) . "' target='_blank' class='file-link'><i class='fas fa-file-pdf file-icon'></i> " . $file_name . "</a>";
                                                } else if (in_array($file_extension, ["doc", "docx"])) {
                                                    echo "<a href='" . base_url("user/getFile/" . urlencode($laporan['file_path'])) . "' target='_blank' class='file-link'><i class='fas fa-file-word file-icon'></i> " . $file_name . "</a>";
                                                } else if (in_array($file_extension, ["xls", "xlsx"])) {
                                                    echo "<a href='" . base_url("user/getFile/" . urlencode($laporan['file_path'])) . "' target='_blank' class='file-link'><i class='fas fa-file-excel file-icon'></i> " . $file_name . "</a>";
                                                } else {
                                                    echo "<a href='" . base_url("user/getFile/" . urlencode($laporan['file_path'])) . "' target='_blank' class='file-link'><i class='fas fa-file file-icon'></i> " . $file_name . "</a>";
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($laporan["keterangan"]); ?></td>
                                            <td>
                                                <?php 
                                                    $status_class = "";
                                                    switch ($laporan["status"]) {
                                                        case "terkirim": $status_class = "bg-info"; break;
                                                        case "disetujui": $status_class = "bg-success"; break;
                                                        case "ditolak": $status_class = "bg-danger"; break;
                                                        default: $status_class = "bg-secondary"; break;
                                                    }
                                                ?>
                                                <span class="badge <?= $status_class; ?> status-badge"><?= ucfirst($laporan["status"]); ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($laporan["feedback"]); ?></td>
                                            <td><?= date("d-m-Y H:i:s", strtotime($laporan["created_at"])); ?></td>
                                            <td>
                                                <?php if ($laporan["status"] == "ditolak"): ?>
                                                    <button type="button" class="btn btn-warning btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#reuploadModal" data-id="<?= $laporan["id"]; ?>" data-nama="<?= htmlspecialchars($laporan["nama_laporan"]); ?>" data-bulan="<?= $laporan["bulan"]; ?>" data-tahun="<?= $laporan["tahun"]; ?>" data-keterangan="<?= htmlspecialchars($laporan["keterangan"]); ?>">
                                                        <i class="fas fa-redo"></i> Re-upload
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-danger btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $laporan["id"]; ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada laporan yang diupload.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Re-upload Modal -->
    <div class="modal fade" id="reuploadModal" tabindex="-1" aria-labelledby="reuploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reuploadModalLabel">Re-upload Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url("user/upload_file/reupload") ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <input type="hidden" name="laporan_id" id="reupload_laporan_id">
                        <div class="mb-3">
                            <label for="reupload_nama_laporan" class="form-label">Nama Laporan</label>
                            <input type="text" class="form-control" id="reupload_nama_laporan" name="nama_laporan" readonly>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="reupload_bulan" class="form-label">Bulan</label>
                                <select class="form-select" id="reupload_bulan" name="bulan" disabled>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i; ?>"><?= getBulanIndo($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="reupload_tahun" class="form-label">Tahun</label>
                                <select class="form-select" id="reupload_tahun" name="tahun" disabled>
                                    <?php for ($i = date("Y"); $i >= 2000; $i--): ?>
                                        <option value="<?= $i; ?>"><?= $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reupload_keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="reupload_keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reupload_files" class="form-label">Pilih File Baru (Max 5MB per file, Max 5 file)</label>
                            <input type="file" class="form-control" id="reupload_files" name="files[]" multiple required>
                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Re-upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus laporan ini?</p>
                    <form action="<?= base_url("user/upload_file/delete") ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="laporan_id_to_delete" id="delete_laporan_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
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
    
    <script>
        $(document).ready(function() {
            $("#dataTableLaporan").DataTable({
                "order": [[7, "desc"]], // Urutkan berdasarkan tanggal upload (kolom ke-8, index 7) secara descending
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

            // Handle Re-upload Modal
            $("#reuploadModal").on("show.bs.modal", function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data("id");
                var nama = button.data("nama");
                var bulan = button.data("bulan");
                var tahun = button.data("tahun");
                var keterangan = button.data("keterangan");

                var modal = $(this);
                modal.find("#reupload_laporan_id").val(id);
                modal.find("#reupload_nama_laporan").val(nama);
                modal.find("#reupload_bulan").val(bulan);
                modal.find("#reupload_tahun").val(tahun);
                modal.find("#reupload_keterangan").val(keterangan);
            });

            // Handle Delete Modal
            $("#deleteModal").on("show.bs.modal", function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data("id");
                var modal = $(this);
                modal.find("#delete_laporan_id").val(id);
            });
        });
    </script>
</body>
</html>