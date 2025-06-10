<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\LaporanFileModel;
use App\Models\NotifikasiModel;
use App\Models\SatkerModel;

class AdminController extends Controller
{
    public function __construct()
    {
        helper(["form", "url", "session", "app_helper"]); // Add app_helper
        $session = session();
        if (!$session->get("isLoggedIn") || $session->get("role") !== "admin") {
            return redirect()->to(base_url("login"));
        }
    }

    public function dashboard()
    {
        $userModel = new UserModel();
        $pegawaiModel = new PegawaiModel();
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();

        $data["user_count"] = $userModel->countAllResults();
        $data["pegawai_count"] = $pegawaiModel->countAllResults();
        $data["laporan_count"] = $laporanFileModel->countAllResults();
        $data["notif_count"] = $notifikasiModel->where("user_id", session()->get("user_id"))->where("is_read", 0)->countAllResults();

        $laporan_terbaru = $laporanFileModel->select("laporan_file.*, users.nama_lengkap")
                                            ->join("users", "users.id = laporan_file.created_by")
                                            ->orderBy("created_at", "DESC")
                                            ->limit(5)
                                            ->findAll();
        $data["laporan_terbaru"] = $laporan_terbaru;

        $status_counts_raw = $laporanFileModel->select("status, COUNT(*) as count")
                                              ->groupBy("status")
                                              ->findAll();
        $status_counts = [];
        foreach ($status_counts_raw as $row) {
            $status_counts[$row["status"]] = $row["count"];
        }
        $data["status_counts"] = $status_counts;

        echo view("admin/dashboard", $data);
    }

    public function kelolaUser()
    {
        $userModel = new UserModel();
        $data["users"] = $userModel->findAll();
        echo view("admin/kelola_user", $data);
    }

    public function addUser()
    {
        $userModel = new UserModel();
        $session = session();

        $rules = [
            "username"      => "required|min_length[3]|max_length[20]|is_unique[users.username]",
            "password"      => "required|min_length[6]",
            "nama_lengkap"  => "required|min_length[3]|max_length[100]",
            "email"         => "required|valid_email|is_unique[users.email]",
            "role"          => "required"
        ];

        if ($this->validate($rules)) {
            $userModel->save([
                "username"      => $this->request->getVar("username"),
                "password"      => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                "nama_lengkap"  => $this->request->getVar("nama_lengkap"),
                "email"         => $this->request->getVar("email"),
                "role"          => $this->request->getVar("role"),
            ]);
            $session->setFlashdata("msg", "User berhasil ditambahkan");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/kelola_user"));
    }

    public function updateUser()
    {
        $userModel = new UserModel();
        $session = session();

        $user_id = $this->request->getVar("user_id");
        $old_user = $userModel->find($user_id);

        $rules = [
            "username"      => "required|min_length[3]|max_length[20]|is_unique[users.username,id,{$user_id}]",
            "nama_lengkap"  => "required|min_length[3]|max_length[100]",
            "email"         => "required|valid_email|is_unique[users.email,id,{$user_id}]",
            "role"          => "required"
        ];

        if ($this->request->getVar("password")) {
            $rules["password"] = "min_length[6]";
        }

        if ($this->validate($rules)) {
            $data = [
                "username"      => $this->request->getVar("username"),
                "nama_lengkap"  => $this->request->getVar("nama_lengkap"),
                "email"         => $this->request->getVar("email"),
                "role"          => $this->request->getVar("role"),
            ];

            if ($this->request->getVar("password")) {
                $data["password"] = password_hash($this->request->getVar("password"), PASSWORD_DEFAULT);
            }

            $userModel->update($user_id, $data);
            $session->setFlashdata("msg", "User berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/kelola_user"));
    }

    public function deleteUser($id = null)
    {
        $userModel = new UserModel();
        $session = session();

        if ($id == $session->get("user_id")) {
            $session->setFlashdata("msg", "Tidak dapat menghapus user yang sedang login");
            $session->setFlashdata("msg_type", "danger");
        } else {
            $userModel->delete($id);
            $session->setFlashdata("msg", "User berhasil dihapus");
            $session->setFlashdata("msg_type", "success");
        }
        return redirect()->to(base_url("admin/kelola_user"));
    }

    public function kelolaLaporan()
    {
        $laporanFileModel = new LaporanFileModel();
        $userModel = new UserModel();
        $session = session();

        $data["users"] = $userModel->where("role", "user")->findAll();

        // Get distinct years for filter
        $tahun_tersedia_raw = $laporanFileModel->distinct()->select("tahun")->orderBy("tahun", "DESC")->findAll();
        $data["tahun_tersedia"] = array_column($tahun_tersedia_raw, "tahun");
        if (empty($data["tahun_tersedia"])) {
            $data["tahun_tersedia"][] = date("Y");
        }

        // Apply filters
        $filter_user = $this->request->getVar("user_id");
        $filter_bulan = $this->request->getVar("bulan");
        $filter_tahun = $this->request->getVar("tahun");

        $laporan = $laporanFileModel->select("laporan_file.*, users.nama_lengkap")
                                    ->join("users", "users.id = laporan_file.created_by");

        if (!empty($filter_user)) {
            $laporan->where("laporan_file.created_by", $filter_user);
            $data["filter_user"] = $filter_user;
        }
        if (!empty($filter_bulan)) {
            $laporan->where("laporan_file.bulan", $filter_bulan);
            $data["filter_bulan"] = $filter_bulan;
        }
        if (!empty($filter_tahun)) {
            $laporan->where("laporan_file.tahun", $filter_tahun);
            $data["filter_tahun"] = $filter_tahun;
        }

        $data["laporan"] = $laporan->orderBy("laporan_file.created_at", "DESC")->findAll();

        echo view("admin/kelola_laporan", $data);
    }

    public function viewLaporan($id = null)
    {
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $laporan = $laporanFileModel->find($id);

        if ($laporan) {
            // If status is 'terkirim', change to 'dilihat' and create notification
            if ($laporan["status"] == "terkirim") {
                $laporanFileModel->update($id, ["status" => "dilihat"]);
                // Create notification for user
                createNotification(
                    $laporan["created_by"],
                    "Laporan Dilihat",
                    "Laporan \"" . $laporan["nama_laporan"] . "\" telah dilihat oleh admin.",
                    "status",
                    $id
                );
            }
            
            $file_path = FCPATH . "assets/uploads/" . $laporan["file_path"];
            if (file_exists($file_path)) {
                return $this->response->download($file_path, null);
            } else {
                $session->setFlashdata("msg", "File laporan tidak ditemukan di server.");
                $session->setFlashdata("msg_type", "danger");
            }
        } else {
            $session->setFlashdata("msg", "Laporan tidak ditemukan.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/kelola_laporan"));
    }

    public function approveLaporan()
    {
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $laporan_id = $this->request->getVar("laporan_id");
        $feedback = $this->request->getVar("feedback");

        if ($laporanFileModel->update($laporan_id, ["status" => "diterima", "feedback" => $feedback])) {
            $laporan = $laporanFileModel->find($laporan_id);
            createNotification(
                $laporan["created_by"],
                "Laporan Diterima",
                "Laporan \"" . $laporan["nama_laporan"] . "\" telah diterima oleh admin.",
                "status",
                $laporan_id
            );
            $session->setFlashdata("msg", "Laporan berhasil disetujui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", "Gagal menyetujui laporan.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/kelola_laporan"));
    }

    public function rejectLaporan()
    {
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $laporan_id = $this->request->getVar("laporan_id");
        $feedback = $this->request->getVar("feedback");

        if ($laporanFileModel->update($laporan_id, ["status" => "ditolak", "feedback" => $feedback])) {
            $laporan = $laporanFileModel->find($laporan_id);
            createNotification(
                $laporan["created_by"],
                "Laporan Ditolak",
                "Laporan \"" . $laporan["nama_laporan"] . "\" telah ditolak oleh admin.",
                "status",
                $laporan_id
            );
            $session->setFlashdata("msg", "Laporan berhasil ditolak");
            $session->setFlashdata("msg_type", "danger");
        } else {
            $session->setFlashdata("msg", "Gagal menolak laporan.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/kelola_laporan"));
    }

    public function deleteLaporan()
    {
        $laporanFileModel = new LaporanFileModel();
        $session = session();

        $laporan_id = $this->request->getVar("laporan_id_to_delete");
        $laporan = $laporanFileModel->find($laporan_id);

        if ($laporan) {
            $file_path = FCPATH . "assets/uploads/" . $laporan["file_path"];
            if ($laporanFileModel->delete($laporan_id)) {
                if (file_exists($file_path) && !empty($laporan["file_path"])) {
                    unlink($file_path);
                }
                $session->setFlashdata("msg", "Laporan berhasil dihapus secara permanen.");
                $session->setFlashdata("msg_type", "success");
            } else {
                $session->setFlashdata("msg", "Gagal menghapus laporan.");
                $session->setFlashdata("msg_type", "danger");
            }
        }
        return redirect()->to(base_url("admin/kelola_laporan"));
    }

    public function rekapKedisiplinan()
    {
        $userModel = new UserModel();
        $laporanFileModel = new LaporanFileModel();
        $satkerModel = new SatkerModel();

        // Get selected year, default to current year
        $tahun_dipilih = $this->request->getVar("tahun") ?? date("Y");

        // Get distinct years for filter
        $daftar_tahun_raw = $laporanFileModel->distinct()->select("tahun")->orderBy("tahun", "DESC")->findAll();
        $data["daftar_tahun"] = array_column($daftar_tahun_raw, "tahun");
        if (!in_array(date("Y"), $data["daftar_tahun"])) {
            array_unshift($data["daftar_tahun"], date("Y"));
        }

        // Get satker list for filter
        $data["satker_list"] = $satkerModel->orderBy("nama_satker", "ASC")->findAll();

        // Apply filters for users
        $filter_user = $this->request->getVar("user_id");
        $filter_satker = $this->request->getVar("satker_id");

        $users_query = $userModel->select("users.id, users.nama_lengkap, satker.nama_satker, satker.id as satker_id")
                                 ->join("satker", "users.satker_id = satker.id", "left")
                                 ->where("users.role", "user");

        if (!empty($filter_user)) {
            $users_query->where("users.id", $filter_user);
            $data["filter_user"] = $filter_user;
        }
        if (!empty($filter_satker)) {
            $users_query->where("users.satker_id", $filter_satker);
            $data["filter_satker"] = $filter_satker;
        }

        $data["users_data"] = $users_query->orderBy("users.nama_lengkap", "ASC")->findAll();
        $data["all_users"] = $userModel->where("role", "user")->orderBy("nama_lengkap", "ASC")->findAll();

        // Get report status per month for the selected year
        $laporan_raw = $laporanFileModel->select("created_by, bulan, status")
                                        ->where("tahun", $tahun_dipilih)
                                        ->findAll();
        $laporan_data = [];
        foreach ($laporan_raw as $row) {
            $laporan_data[$row["created_by"]][$row["bulan"]] = $row["status"];
        }
        $data["laporan_data"] = $laporan_data;
        $data["tahun_dipilih"] = $tahun_dipilih;

        $data["nama_bulan"] = [
            1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "Mei", 6 => "Jun",
            7 => "Jul", 8 => "Agu", 9 => "Sep", 10 => "Okt", 11 => "Nov", 12 => "Des"
        ];

        echo view("admin/rekap_kedisiplinan", $data);
    }

    public function notifikasi()
    {
        $notifikasiModel = new NotifikasiModel();
        $session = session();
        $admin_user_id = $session->get("user_id");

        // Delete old notifications (older than 3 days)
        $three_days_ago = date("Y-m-d H:i:s", strtotime("-3 days"));
        $notifikasiModel->where("user_id", $admin_user_id)
                        ->where("created_at <", $three_days_ago)
                        ->delete();

        // Get all notifications for the admin
        $data["notifikasi"] = $notifikasiModel->where("user_id", $admin_user_id)
                                            ->orderBy("created_at", "DESC")
                                            ->findAll();

        // Mark all displayed notifications as read
        $notifikasiModel->where("user_id", $admin_user_id)
                        ->where("is_read", 0)
                        ->set(["is_read" => 1])
                        ->update();
        
        // Recalculate notif_count for sidebar after marking as read
        $session->set("notif_count", $notifikasiModel->where("user_id", $admin_user_id)->where("is_read", 0)->countAllResults());

        echo view("admin/notifikasi", $data);
    }

    public function profil()
    {
        $userModel = new UserModel();
        $session = session();
        $user_id = $session->get("user_id");
        $data["user"] = $userModel->find($user_id);
        echo view("admin/profil", $data);
    }

    public function updateProfil()
    {
        $userModel = new UserModel();
        $session = session();
        $user_id = $session->get("user_id");

        $rules = [
            "username"      => "required|min_length[3]|max_length[20]|is_unique[users.username,id,{$user_id}]",
            "nama_lengkap"  => "required|min_length[3]|max_length[100]",
            "email"         => "required|valid_email|is_unique[users.email,id,{$user_id}]",
        ];

        $password_lama = $this->request->getVar("password_lama");
        $password_baru = $this->request->getVar("password_baru");
        $konfirmasi_password = $this->request->getVar("konfirmasi_password");

        if (!empty($password_lama) || !empty($password_baru) || !empty($konfirmasi_password)) {
            $user = $userModel->find($user_id);
            if (!password_verify($password_lama, $user["password"])) {
                $session->setFlashdata("msg", "Password lama salah");
                $session->setFlashdata("msg_type", "danger");
                return redirect()->to(base_url("admin/profil"));
            }
            if ($password_baru != $konfirmasi_password) {
                $session->setFlashdata("msg", "Konfirmasi password tidak sesuai");
                $session->setFlashdata("msg_type", "danger");
                return redirect()->to(base_url("admin/profil"));
            }
            $rules["password_baru"] = "min_length[6]";
        }

        if ($this->validate($rules)) {
            $data = [
                "username"      => $this->request->getVar("username"),
                "nama_lengkap"  => $this->request->getVar("nama_lengkap"),
                "email"         => $this->request->getVar("email"),
            ];

            if (!empty($password_baru)) {
                $data["password"] = password_hash($password_baru, PASSWORD_DEFAULT);
            }

            $userModel->update($user_id, $data);
            $session->set("username", $this->request->getVar("username"));
            $session->set("nama_lengkap", $this->request->getVar("nama_lengkap"));
            $session->setFlashdata("msg", "Profil berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/profil"));
    }

    public function updateFotoProfil()
    {
        $userModel = new UserModel();
        $session = session();
        $user_id = $session->get("user_id");

        $file = $this->request->getFile("foto_profil");

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $user_id . "_" . time() . "_" . $file->getName();
            $file->move(FCPATH . "assets/img", $newName);

            $userModel->update($user_id, ["foto_profil" => $newName]);
            $session->set("foto_profil", $newName);
            $session->setFlashdata("msg", "Foto profil berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", "Gagal mengupload file: " . $file->getErrorString());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("admin/profil"));
    }
}


