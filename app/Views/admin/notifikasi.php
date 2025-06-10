<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Admin - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/css/sidebar_styles.css") ?>"> 
    <style>
        /* Styles specific to notification page */
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #e8f4fd; /* Light blue for unread */
            font-weight: bold; /* Make unread items bold */
        }
        .notification-item .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #343a40;
        }
        .notification-item.unread .notification-title {
             font-weight: 900; /* Even bolder title for unread */
        }
        .notification-item .notification-time {
            font-size: 12px;
            color: #6c757d;
        }
        .notification-item .notification-message {
            margin-bottom: 5px;
            color: #495057;
            font-weight: normal; /* Ensure message is normal weight */
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
        /* Ensure white bell icon in sidebar link */
        .sidebar-menu li a i.fa-bell {
            color: #ffffff !important; /* Force white color */
        }
         /* Ensure white text for notification badge */
        .notification-badge {
             color: #fff !important; 
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
        <!-- UPDATED ADMIN SIDEBAR -->
        <ul class="sidebar-menu mt-4">
            <li><a href="<?= base_url("admin/dashboard") ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="<?= base_url("admin/kelola_user") ?>"><i class="fas fa-users"></i> <span>Kelola User</span></a></li>
            <li><a href="<?= base_url("admin/kelola_laporan") ?>"><i class="fas fa-file-alt"></i> <span>Kelola Laporan</span></a></li>
            <li><a href="<?= base_url("admin/rekap_kedisiplinan") ?>"><i class="fas fa-calendar-check"></i> <span>Rekap Kedisiplinan</span></a></li>
            <li>
                <a href="<?= base_url("admin/notifikasi") ?>" class="active"> 
                    <i class="fas fa-bell" style="color: white;"></i> <span>Notifikasi</span>
                    <?php if (session()->get("notif_count") > 0): ?>
                        <span class="notification-badge"><?= session()->get("notif_count"); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url("admin/profil") ?>"><i class="fas fa-user-cog"></i> <span>Pengaturan Profil</span></a></li>
            <li><a href="<?= base_url("logout") ?>"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
        <!-- Internal Sidebar Toggle Button -->
        
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                
                <h5 class="navbar-brand mb-0">Notifikasi Admin</h5>
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
            
            <!-- Notifikasi -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Notifikasi (Otomatis terhapus setelah 3 hari)</span>
                     <!-- Optional: Show how many were marked as read -->
                     <!-- <?php if (isset($marked_as_read_count) && $marked_as_read_count > 0) echo "<span class=\'badge bg-info ms-2\'>$marked_as_read_count notifikasi baru ditandai terbaca</span>"; ?> -->
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($notifikasi)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifikasi as $row): 
                                // --- ADJUSTED ADMIN LINKS ---
                                $link = base_url("admin/dashboard"); // Default link
                                $target = "_self";
                                if ($row["jenis"] == "laporan" && !empty($row["referensi_id"])) {
                                    // Admin clicks on \'laporan\' notification -> go to kelola_laporan.php
                                    // Optionally, add filter/anchor to highlight the specific report
                                    $link = base_url("admin/kelola_laporan?highlight=" . $row["referensi_id"]); // Example: use a GET param
                                } elseif ($row["jenis"] == "sistem") {
                                    // System notifications might link to dashboard or be non-clickable
                                    $link = base_url("admin/dashboard");
                                } // Other types like \'status\' or \'feedback\' might not be relevant for admin notifications, or link elsewhere
                                // --- End Admin Links ---
                            ?>
                                <a href="<?= $link; ?>" target="<?= $target; ?>" class="list-group-item list-group-item-action notification-item <?= $row["is_read"] == 0 ? "unread" : ""; ?>" data-notif-id="<?= $row["id"]; ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 notification-title"><?= htmlspecialchars($row["judul"]); ?></h5>
                                        <small class="notification-time"><i class="far fa-clock me-1"></i> <?= timeAgo($row["created_at"]); ?></small>
                                    </div>
                                    <p class="mb-1 notification-message"><?= htmlspecialchars($row["pesan"]); ?></p>
                                    <!-- <small>Jenis: <?= $row["jenis"]; ?> Ref: <?= $row["referensi_id"]; ?></small> -->
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
    
    <!-- Custom Sidebar JS (using the same logic as user side) -->
    <script>
       $(document).ready(function() {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("mainContent");
            const sidebarToggle = document.getElementById("sidebarToggle"); // Navbar toggle
            const sidebarInternalToggle = document.getElementById("sidebarInternalToggle"); // Internal toggle
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
            if (sidebarInternalToggle) {
                sidebarInternalToggle.addEventListener("click", toggleSidebar);
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
                     mainContent.style.marginLeft = sidebar.classList.contains("collapsed") ? "80px" : "250px";
                     overlay.classList.remove("active");
                }
            }

            window.addEventListener("resize", checkWindowSize);
            checkWindowSize(); // Initial check

            // Highlight referenced report if URL parameter exists (Example)
            const urlParams = new URLSearchParams(window.location.search);
            const highlightId = urlParams.get("highlight");
            if (highlightId) {
                // Find the table row or element related to this ID and highlight it
                // This requires the target page (kelola_laporan.php) to have elements 
                // identifiable by the report ID (e.g., <tr id=\"report-row-${highlightId}\">)
                const targetElement = document.getElementById(`report-row-${highlightId}`); 
                if (targetElement) {
                    targetElement.style.backgroundColor = "#ffffcc"; // Example highlight
                    targetElement.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }
        });
    </script>
</body>
</html>


