<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PegawaiModel;
use App\Models\KedisiplinanModel;
use App\Models\LaporanFileModel;
use App\Models\NotifikasiModel;
use App\Models\SatkerModel;
use App\Models\TandaTanganModel;
use TCPDF; // Tambahkan use untuk TCPDF
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Tambahkan use untuk PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function __construct()
    {
        helper(["form", "url", "session", "app_helper"]);
        $session = session();
        if (!$session->get("isLoggedIn") || $session->get("role") !== "user") {
            return redirect()->to(base_url("login"));
        }
    }

    public function dashboard()
    {
        $pegawaiModel = new PegawaiModel();
        $kedisiplinanModel = new KedisiplinanModel();
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $satkerModel = new SatkerModel();

        $data["pegawai_count"] = $pegawaiModel->where("created_by", session()->get("user_id"))->countAllResults();
        $data["kedisiplinan_count"] = $kedisiplinanModel->where("created_by", session()->get("user_id"))->countAllResults();
        $data["laporan_count"] = $laporanFileModel->where("created_by", session()->get("user_id"))->countAllResults();
        $data["notif_count"] = $notifikasiModel->where("user_id", session()->get("user_id"))->where("is_read", 0)->countAllResults();

        $laporan_terbaru = $laporanFileModel->where("created_by", session()->get("user_id"))
                                            ->orderBy("created_at", "DESC")
                                            ->limit(5)
                                            ->findAll();
        $data["laporan_terbaru"] = $laporan_terbaru;

        $satker_list = $satkerModel->findAll();
        $satker_data = [];
        foreach ($satker_list as $satker) {
            $count = $pegawaiModel->where("satker_id", $satker["id"])->where("created_by", session()->get("user_id"))->countAllResults();
            $satker["jumlah_pegawai"] = $count;
            $satker_data[] = $satker;
        }
        $data["satker_list"] = $satker_data;

        echo view("user/dashboard", $data);
    }

    public function inputPegawai()
    {
        $pegawaiModel = new PegawaiModel();
        $satkerModel = new SatkerModel();
        $session = session();

        $data["satker_list"] = $satkerModel->orderBy("nama_satker", "ASC")->findAll();
        $data["pegawai"] = $pegawaiModel->select("pegawai.*, satker.nama_satker")
                                        ->join("satker", "pegawai.satker_id = satker.id")
                                        ->where("pegawai.created_by", $session->get("user_id"))
                                        ->orderBy("pegawai.nama", "ASC")
                                        ->findAll();

        echo view("user/input_pegawai", $data);
    }

    public function addSatker()
    {
        $satkerModel = new SatkerModel();
        $session = session();

        $rules = [
            "nama_satker" => "required|is_unique[satker.nama_satker]",
            "alamat"      => "required"
        ];

        if ($this->validate($rules)) {
            $satkerModel->save([
                "nama_satker" => $this->request->getVar("nama_satker"),
                "alamat"      => $this->request->getVar("alamat"),
            ]);
            $session->setFlashdata("msg", "Satuan Kerja berhasil ditambahkan");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_pegawai"));
    }

    public function addPegawai()
    {
        $pegawaiModel = new PegawaiModel();
        $session = session();

        $rules = [
            "nama"      => "required",
            "nip"       => "required|is_unique[pegawai.nip]",
            "pangkat"   => "required",
            "golongan"  => "required",
            "jabatan"   => "required",
            "satker_id" => "required"
        ];

        if ($this->validate($rules)) {
            $pegawaiModel->save([
                "nama"       => $this->request->getVar("nama"),
                "nip"        => $this->request->getVar("nip"),
                "pangkat"    => $this->request->getVar("pangkat"),
                "golongan"   => $this->request->getVar("golongan"),
                "jabatan"    => $this->request->getVar("jabatan"),
                "satker_id"  => $this->request->getVar("satker_id"),
                "created_by" => $session->get("user_id"),
            ]);
            $session->setFlashdata("msg", "Pegawai berhasil ditambahkan");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_pegawai"));
    }

    public function updatePegawai()
    {
        $pegawaiModel = new PegawaiModel();
        $session = session();

        $pegawai_id = $this->request->getVar("pegawai_id");

        $rules = [
            "nama"      => "required",
            "nip"       => "required|is_unique[pegawai.nip,id,{$pegawai_id}]",
            "pangkat"   => "required",
            "golongan"  => "required",
            "jabatan"   => "required",
            "satker_id" => "required"
        ];

        if ($this->validate($rules)) {
            $data = [
                "nama"       => $this->request->getVar("nama"),
                "nip"        => $this->request->getVar("nip"),
                "pangkat"    => $this->request->getVar("pangkat"),
                "golongan"   => $this->request->getVar("golongan"),
                "jabatan"    => $this->request->getVar("jabatan"),
                "satker_id"  => $this->request->getVar("satker_id"),
            ];

            $pegawaiModel->update($pegawai_id, $data);
            $session->setFlashdata("msg", "Pegawai berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_pegawai"));
    }

    public function deletePegawai($id = null)
    {
        $pegawaiModel = new PegawaiModel();
        $session = session();

        $pegawai = $pegawaiModel->find($id);

        if ($pegawai && $pegawai["created_by"] == $session->get("user_id")) {
            $pegawaiModel->delete($id);
            $session->setFlashdata("msg", "Pegawai berhasil dihapus");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", "Pegawai tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_pegawai"));
    }

    public function inputKedisiplinan()
    {
        $pegawaiModel = new PegawaiModel();
        $kedisiplinanModel = new KedisiplinanModel();
        $satkerModel = new SatkerModel();
        $session = session();

        $data["pegawai_list"] = $pegawaiModel->where("created_by", $session->get("user_id"))->orderBy("nama", "ASC")->findAll();
        $data["satker_list"] = $satkerModel->orderBy("nama_satker", "ASC")->findAll();

        // Get distinct years for filter
        $tahun_tersedia_raw = $kedisiplinanModel->distinct()->select("tahun")->where("created_by", $session->get("user_id"))->orderBy("tahun", "DESC")->findAll();
        $data["tahun_tersedia"] = array_column($tahun_tersedia_raw, "tahun");
        if (empty($data["tahun_tersedia"])) {
            $data["tahun_tersedia"][] = date("Y");
        }

        // Apply filters
        $filter_satker = $this->request->getVar("satker");
        $filter_bulan = $this->request->getVar("bulan") ?? date("n");
        $filter_tahun = $this->request->getVar("tahun") ?? (empty($data["tahun_tersedia"]) ? date("Y") : $data["tahun_tersedia"][0]);

        $kedisiplinan_query = $kedisiplinanModel->select("kedisiplinan.*, pegawai.nama, pegawai.nip, pegawai.pangkat, pegawai.golongan, pegawai.jabatan, satker.nama_satker")
                                                ->join("pegawai", "kedisiplinan.pegawai_id = pegawai.id")
                                                ->join("satker", "pegawai.satker_id = satker.id")
                                                ->where("kedisiplinan.created_by", $session->get("user_id"))
                                                ->where("kedisiplinan.bulan", $filter_bulan)
                                                ->where("kedisiplinan.tahun", $filter_tahun);

        if (!empty($filter_satker)) {
            $kedisiplinan_query->where("pegawai.satker_id", $filter_satker);
            $data["filter_satker"] = $filter_satker;
        }
        $data["filter_bulan"] = $filter_bulan;
        $data["filter_tahun"] = $filter_tahun;

        $data["kedisiplinan_data"] = $kedisiplinan_query->orderBy("pegawai.nama", "ASC")->findAll();

        echo view("user/input_kedisiplinan", $data);
    }

    public function addKedisiplinan()
    {
        $kedisiplinanModel = new KedisiplinanModel();
        $session = session();

        $rules = [
            "pegawai_id"            => "required",
            "bulan"                 => "required",
            "tahun"                 => "required",
            "terlambat"             => "required|numeric",
            "tidak_absen_masuk"     => "required|numeric",
            "pulang_awal"           => "required|numeric",
            "tidak_absen_pulang"    => "required|numeric",
            "keluar_tidak_izin"     => "required|numeric",
            "tidak_masuk_tanpa_ket" => "required|numeric",
            "tidak_masuk_sakit"     => "required|numeric",
            "tidak_masuk_kerja"     => "required|numeric",
            "bentuk_pembinaan"      => "permit_empty",
            "keterangan"            => "permit_empty",
        ];

        // Custom rule to check for unique combination of pegawai_id, bulan, and tahun
        $pegawai_id = $this->request->getVar("pegawai_id");
        $bulan = $this->request->getVar("bulan");
        $tahun = $this->request->getVar("tahun");

        $existing_data = $kedisiplinanModel->where(["pegawai_id" => $pegawai_id, "bulan" => $bulan, "tahun" => $tahun])->first();
        if ($existing_data) {
            $session->setFlashdata("msg", "Data kedisiplinan untuk pegawai, bulan, dan tahun tersebut sudah ada.");
            $session->setFlashdata("msg_type", "danger");
            return redirect()->to(base_url("user/input_kedisiplinan"));
        }

        if ($this->validate($rules)) {
            $kedisiplinanModel->save([
                "pegawai_id"            => $pegawai_id,
                "bulan"                 => $bulan,
                "tahun"                 => $tahun,
                "terlambat"             => $this->request->getVar("terlambat"),
                "tidak_absen_masuk"     => $this->request->getVar("tidak_absen_masuk"),
                "pulang_awal"           => $this->request->getVar("pulang_awal"),
                "tidak_absen_pulang"    => $this->request->getVar("tidak_absen_pulang"),
                "keluar_tidak_izin"     => $this->request->getVar("keluar_tidak_izin"),
                "tidak_masuk_tanpa_ket" => $this->request->getVar("tidak_masuk_tanpa_ket"),
                "tidak_masuk_sakit"     => $this->request->getVar("tidak_masuk_sakit"),
                "tidak_masuk_kerja"     => $this->request->getVar("tidak_masuk_kerja"),
                "bentuk_pembinaan"      => $this->request->getVar("bentuk_pembinaan"),
                "keterangan"            => $this->request->getVar("keterangan"),
                "created_by"            => $session->get("user_id"),
            ]);
            $session->setFlashdata("msg", "Data kedisiplinan berhasil ditambahkan");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_kedisiplinan"));
    }

    public function updateKedisiplinan()
    {
        $kedisiplinanModel = new KedisiplinanModel();
        $session = session();

        $kedisiplinan_id = $this->request->getVar("kedisiplinan_id");

        $rules = [
            "terlambat"             => "required|numeric",
            "tidak_absen_masuk"     => "required|numeric",
            "pulang_awal"           => "required|numeric",
            "tidak_absen_pulang"    => "required|numeric",
            "keluar_tidak_izin"     => "required|numeric",
            "tidak_masuk_tanpa_ket" => "required|numeric",
            "tidak_masuk_sakit"     => "required|numeric",
            "tidak_masuk_kerja"     => "required|numeric",
            "bentuk_pembinaan"      => "permit_empty",
            "keterangan"            => "permit_empty",
        ];

        if ($this->validate($rules)) {
            $data = [
                "terlambat"             => $this->request->getVar("terlambat"),
                "tidak_absen_masuk"     => $this->request->getVar("tidak_absen_masuk"),
                "pulang_awal"           => $this->request->getVar("pulang_awal"),
                "tidak_absen_pulang"    => $this->request->getVar("tidak_absen_pulang"),
                "keluar_tidak_izin"     => $this->request->getVar("keluar_tidak_izin"),
                "tidak_masuk_tanpa_ket" => $this->request->getVar("tidak_masuk_tanpa_ket"),
                "tidak_masuk_sakit"     => $this->request->getVar("tidak_masuk_sakit"),
                "tidak_masuk_kerja"     => $this->request->getVar("tidak_masuk_kerja"),
                "bentuk_pembinaan"      => $this->request->getVar("bentuk_pembinaan"),
                "keterangan"            => $this->request->getVar("keterangan"),
            ];

            $kedisiplinanModel->update($kedisiplinan_id, $data);
            $session->setFlashdata("msg", "Data kedisiplinan berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_kedisiplinan"));
    }

    public function deleteKedisiplinan($id = null)
    {
        $kedisiplinanModel = new KedisiplinanModel();
        $session = session();

        $kedisiplinan = $kedisiplinanModel->find($id);

        if ($kedisiplinan && $kedisiplinan["created_by"] == $session->get("user_id")) {
            $kedisiplinanModel->delete($id);
            $session->setFlashdata("msg", "Data kedisiplinan berhasil dihapus");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", "Data kedisiplinan tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_kedisiplinan"));
    }

    public function inputTandaTangan()
    {
        $tandaTanganModel = new TandaTanganModel();
        $pegawaiModel = new PegawaiModel();
        $session = session();

        $data["tanda_tangan_data"] = $tandaTanganModel->where("created_by", $session->get("user_id"))->orderBy("id", "DESC")->findAll();
        $data["pegawai_list"] = $pegawaiModel->where("created_by", $session->get("user_id"))->orderBy("nama", "ASC")->findAll();

        echo view("user/input_tanda_tangan", $data);
    }

    public function addTandaTangan()
    {
        $tandaTanganModel = new TandaTanganModel();
        $session = session();

        $rules = [
            "lokasi"             => "required",
            "tanggal"            => "required|valid_date",
            "nama_jabatan"       => "required",
            "nama_penandatangan" => "required",
            "nip_penandatangan"  => "required",
        ];

        if ($this->validate($rules)) {
            $tandaTanganModel->save([
                "lokasi"             => $this->request->getVar("lokasi"),
                "tanggal"            => $this->request->getVar("tanggal"),
                "nama_jabatan"       => $this->request->getVar("nama_jabatan"),
                "nama_penandatangan" => $this->request->getVar("nama_penandatangan"),
                "nip_penandatangan"  => $this->request->getVar("nip_penandatangan"),
                "created_by"         => $session->get("user_id"),
            ]);
            $session->setFlashdata("msg", "Data tanda tangan berhasil ditambahkan");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_tanda_tangan"));
    }

    public function updateTandaTangan()
    {
        $tandaTanganModel = new TandaTanganModel();
        $session = session();

        $tanda_tangan_id = $this->request->getVar("tanda_tangan_id");

        $rules = [
            "lokasi"             => "required",
            "tanggal"            => "required|valid_date",
            "nama_jabatan"       => "required",
            "nama_penandatangan" => "required",
            "nip_penandatangan"  => "required",
        ];

        if ($this->validate($rules)) {
            $data = [
                "lokasi"             => $this->request->getVar("lokasi"),
                "tanggal"            => $this->request->getVar("tanggal"),
                "nama_jabatan"       => $this->request->getVar("nama_jabatan"),
                "nama_penandatangan" => $this->request->getVar("nama_penandatangan"),
                "nip_penandatangan"  => $this->request->getVar("nip_penandatangan"),
            ];

            $tandaTanganModel->update($tanda_tangan_id, $data);
            $session->setFlashdata("msg", "Data tanda tangan berhasil diperbarui");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_tanda_tangan"));
    }

    public function deleteTandaTangan($id = null)
    {
        $tandaTanganModel = new TandaTanganModel();
        $session = session();

        $tanda_tangan = $tandaTanganModel->find($id);

        if ($tanda_tangan && $tanda_tangan["created_by"] == $session->get("user_id")) {
            $tandaTanganModel->delete($id);
            $session->setFlashdata("msg", "Data tanda tangan berhasil dihapus");
            $session->setFlashdata("msg_type", "success");
        } else {
            $session->setFlashdata("msg", "Data tanda tangan tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.");
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/input_tanda_tangan"));
    }

    public function rekapLaporan()
    {
        $kedisiplinanModel = new KedisiplinanModel();
        $satkerModel = new SatkerModel();
        $tandaTanganModel = new TandaTanganModel();
        $session = session();

        // Ambil tahun unik dari database untuk filter
        $tahun_tersedia_raw = $kedisiplinanModel->distinct()->select("tahun")->where("created_by", $session->get("user_id"))->orderBy("tahun", "DESC")->findAll();
        $data["tahun_tersedia"] = array_column($tahun_tersedia_raw, "tahun");

        // Jika tidak ada tahun di database, tambahkan tahun sekarang
        if (empty($data["tahun_tersedia"])) {
            $data["tahun_tersedia"][] = date("Y");
        }

        // Filter data rekap
        $filter_satker = $this->request->getVar("satker");
        $filter_bulan = $this->request->getVar("bulan") ?? date("n");
        $filter_tahun = $this->request->getVar("tahun") ?? (empty($data["tahun_tersedia"]) ? date("Y") : $data["tahun_tersedia"][0]);

        $data["satker_list"] = $satkerModel->orderBy("nama_satker", "ASC")->findAll();

        $kedisiplinan_query = $kedisiplinanModel->select("kedisiplinan.*, pegawai.nama, pegawai.nip, pegawai.pangkat, pegawai.golongan, pegawai.jabatan, satker.nama_satker")
                                                ->join("pegawai", "kedisiplinan.pegawai_id = pegawai.id")
                                                ->join("satker", "pegawai.satker_id = satker.id")
                                                ->where("kedisiplinan.created_by", $session->get("user_id"))
                                                ->where("kedisiplinan.bulan", $filter_bulan)
                                                ->where("kedisiplinan.tahun", $filter_tahun);

        // Always define filter_satker, even if empty
        $data["filter_satker"] = !empty($filter_satker) ? $filter_satker : '';
        if (!empty($filter_satker)) {
            $kedisiplinan_query->where("pegawai.satker_id", $filter_satker);
        }
        $data["filter_bulan"] = $filter_bulan;
        $data["filter_tahun"] = $filter_tahun;

        $data["kedisiplinan_data"] = $kedisiplinan_query->orderBy("pegawai.nama", "ASC")->findAll();

        $data["tanda_tangan"] = $tandaTanganModel->where("created_by", $session->get("user_id"))->orderBy("id", "DESC")->first();

        echo view("user/rekap_laporan", $data);
    }

    public function exportPdf()
{
    $kedisiplinanModel = new KedisiplinanModel();
    $satkerModel = new SatkerModel();
    $tandaTanganModel = new TandaTanganModel();
    $session = session();

    // Ambil filter dari request
    $filter_satker = $this->request->getPost("satker");
    $filter_bulan = (int)($this->request->getPost("bulan") ?? date("n")); // Konversi ke integer
    $filter_tahun = $this->request->getPost("tahun") ?? date("Y");

    $kedisiplinan_query = $kedisiplinanModel->select("kedisiplinan.*, pegawai.nama, pegawai.nip, pegawai.pangkat, pegawai.golongan, pegawai.jabatan, satker.nama_satker")
                                            ->join("pegawai", "kedisiplinan.pegawai_id = pegawai.id")
                                            ->join("satker", "pegawai.satker_id = satker.id")
                                            ->where("kedisiplinan.created_by", $session->get("user_id"))
                                            ->where("kedisiplinan.bulan", $filter_bulan)
                                            ->where("kedisiplinan.tahun", $filter_tahun);

    if (!empty($filter_satker)) {
        $kedisiplinan_query->where("pegawai.satker_id", $filter_satker);
    }

    $data["kedisiplinan_data"] = $kedisiplinan_query->orderBy("pegawai.nama", "ASC")->findAll();
    $data["filter_bulan"] = $filter_bulan;
    $data["filter_tahun"] = $filter_tahun;
    $data["filter_satker_name"] = $filter_satker ? $satkerModel->find($filter_satker)["nama_satker"] : "Semua Satker";
    $data["tanda_tangan"] = $tandaTanganModel->where("created_by", $session->get("user_id"))->orderBy("id", "DESC")->first();

    // Inisialisasi TCPDF dengan custom class
    $pdf = new \App\Libraries\PDF('L', 'mm', 'A4');
    $pdf->SetCreator('Sistem Manajemen Disiplin Hakim');
    $pdf->SetTitle('Rekap Laporan Disiplin Hakim');
    $pdf->SetHeaderData('', 0, 'Rekap Laporan Disiplin Hakim', '');

    // Set properties SEBELUM AddPage
    $pdf->current_bulan = $filter_bulan;
    $pdf->current_tahun = $filter_tahun;
    $pdf->current_satker_name = $data["filter_satker_name"];

    $pdf->setHeaderFont(array('helvetica', '', 10));
    $pdf->setFooterFont(array('helvetica', '', 8));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetMargins(10, 35, 20); // Tingkatkan margin bawah untuk memberi ruang ke tanda tangan
    $pdf->SetAutoPageBreak(TRUE, 15);

    $max_rows_per_page = 7;
    $total_rows = count($data["kedisiplinan_data"]);
    $pages = ceil($total_rows / $max_rows_per_page);

    for ($page = 0; $page < $pages; $page++) {
        $pdf->AddPage();
        $pdf->drawComplexHeader();
        $pdf->SetFont('helvetica', '', 7);

        $start_row = $page * $max_rows_per_page;
        $end_row = min(($page + 1) * $max_rows_per_page, $total_rows);
        $row_number = $start_row + 1;

        for ($i = $start_row; $i < $end_row; $i++) {
            $row = $data["kedisiplinan_data"][$i];
            $nama_nip = $row['nama'] . "\nNIP. " . $row['nip'];
            $pangkat_gol = $row['pangkat'] . "\n" . $row['golongan'];

            $row_data = [
            $row_number++,
            $nama_nip,
            $pangkat_gol,
            $row['jabatan'],
            $row['nama_satker'],
            $row['terlambat'] > 0 ? $row['terlambat'] : '-',
            $row['keluar_tidak_izin'] > 0 ? $row['keluar_tidak_izin'] : '-',
            $row['tidak_absen_masuk'] > 0 ? $row['tidak_absen_masuk'] : '-',
            $row['tidak_masuk_tanpa_ket'] > 0 ? $row['tidak_masuk_tanpa_ket'] : '-',
            $row['pulang_awal'] > 0 ? $row['pulang_awal'] : '-',
            $row['tidak_masuk_sakit'] > 0 ? $row['tidak_masuk_sakit'] : '-',
            $row['tidak_absen_pulang'] > 0 ? $row['tidak_absen_pulang'] : '-',
            $row['tidak_masuk_kerja'] > 0 ? $row['tidak_masuk_kerja'] : '-',
            !empty($row['bentuk_pembinaan']) ? $row['bentuk_pembinaan'] : '-',
            !empty($row['keterangan']) ? $row['keterangan'] : '-'
        ];

            $pdf->drawTableRow($row_data, 10);
        }

        // Tambahkan KETERANGAN di setiap halaman
        $pdf->drawKeterangan();

        // Tambahkan TEMPAT TANDA TANGAN di setiap halaman
        if ($data["tanda_tangan"]) {
            $pdf->drawTandaTangan($data["tanda_tangan"]);
        }
    }

    // Output PDF
    $filename = 'Rekap_Laporan_Disiplin_Hakim_' . getBulanIndo($filter_bulan) . '_' . $filter_tahun . '.pdf';
    $pdf->Output($filename, 'D');
    exit;
}

    public function exportExcel()
{
    $kedisiplinanModel = new KedisiplinanModel();
    $satkerModel = new SatkerModel();
    $tandaTanganModel = new TandaTanganModel();
    $session = session();

    // Ambil filter dari request
    $filter_satker = $this->request->getPost("satker");
    $filter_bulan = $this->request->getPost("bulan") ?? date("n");
    $filter_tahun = $this->request->getPost("tahun") ?? date("Y");

    $kedisiplinan_query = $kedisiplinanModel->select("kedisiplinan.*, pegawai.nama, pegawai.nip, pegawai.pangkat, pegawai.golongan, pegawai.jabatan, satker.nama_satker")
        ->join("pegawai", "kedisiplinan.pegawai_id = pegawai.id")
        ->join("satker", "pegawai.satker_id = satker.id")
        ->where("kedisiplinan.created_by", $session->get("user_id"))
        ->where("kedisiplinan.bulan", $filter_bulan)
        ->where("kedisiplinan.tahun", $filter_tahun);

    if (!empty($filter_satker)) {
        $kedisiplinan_query->where("pegawai.satker_id", $filter_satker);
    }

    $data["kedisiplinan_data"] = $kedisiplinan_query->orderBy("pegawai.nama", "ASC")->findAll();
    $data["filter_bulan"] = $filter_bulan;
    $data["filter_tahun"] = $filter_tahun;
    $data["filter_satker_name"] = $filter_satker ? $satkerModel->find($filter_satker)["nama_satker"] : "Semua Satker";
    $data["tanda_tangan"] = $tandaTanganModel->where("created_by", $session->get("user_id"))->orderBy("id", "DESC")->first();

    // Inisialisasi PHPSpreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Rekap Laporan');

    // Add logo to header with increased height
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo');
    $drawing->setPath(FCPATH . 'assets/img/logo.png');
    $drawing->setHeight(75); // Increased height for better visibility
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(10);
    $drawing->setOffsetY(5);
    $drawing->setWorksheet($sheet);

    // Header with font size 9
    $sheet->setCellValue('C1', 'LAPORAN DISIPLIN HAKIM');
    $sheet->mergeCells('C1:O1');
    $sheet->getStyle('C1')->getFont()->setSize(9)->setBold(true);
    $sheet->getStyle('C1')->getAlignment()->setHorizontal('center')->setVertical('center');

    $sheet->setCellValue('C2', 'YANG TIDAK MEMATUHI KETENTUAN JAM KERJA SESUAI DENGAN PERMA NO 7 TAHUN 2016');
    $sheet->mergeCells('C2:O2');
    $sheet->getStyle('C2')->getFont()->setSize(9)->setBold(true);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal('center')->setVertical('center');

    $sheet->setCellValue('C3', 'BULAN : ' . strtoupper(date('F Y', mktime(0, 0, 0, $filter_bulan, 1, $filter_tahun))));
    $sheet->mergeCells('C3:O3');
    $sheet->getStyle('C3')->getFont()->setSize(9)->setBold(true);
    $sheet->getStyle('C3')->getAlignment()->setHorizontal('center')->setVertical('center');

    if (!empty($data["filter_satker_name"]) && $data["filter_satker_name"] !== "Semua Satker") {
        $sheet->setCellValue('C4', 'SATKER : ' . strtoupper($data["filter_satker_name"]));
        $sheet->mergeCells('C4:O4');
        $sheet->getStyle('C4')->getFont()->setSize(9)->setBold(true);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal('center')->setVertical('center');
        $startRow = 6;
    } else {
        $startRow = 5;
    }

    // Header tabel with font size 9
    // Baris pertama header (merge untuk kolom A-E dan F-M)
    $sheet->setCellValue('A' . $startRow, 'NO');
    $sheet->mergeCells('A' . $startRow . ':A' . ($startRow + 1));
    
    $sheet->setCellValue('B' . $startRow, 'NAMA/NIP');
    $sheet->mergeCells('B' . $startRow . ':B' . ($startRow + 1));
    
    $sheet->setCellValue('C' . $startRow, 'PANGKAT/GOL. RUANG');
    $sheet->mergeCells('C' . $startRow . ':C' . ($startRow + 1));
    
    $sheet->setCellValue('D' . $startRow, 'JABATAN');
    $sheet->mergeCells('D' . $startRow . ':D' . ($startRow + 1));
    
    $sheet->setCellValue('E' . $startRow, 'SATUAN KERJA');
    $sheet->mergeCells('E' . $startRow . ':E' . ($startRow + 1));

    // Header grup untuk kolom F-M
    $sheet->setCellValue('F' . $startRow, 'URAIAN AKUMULASI TIDAK DIPATUHKANNYA JAM KERJA DALAM 1 BULAN');
    $sheet->mergeCells('F' . $startRow . ':M' . $startRow);
    
    $sheet->setCellValue('N' . $startRow, 'BENTUK PEMBINAAN');
    $sheet->mergeCells('N' . $startRow . ':N' . ($startRow + 1));
    
    $sheet->setCellValue('O' . $startRow, 'KET');
    $sheet->mergeCells('O' . $startRow . ':O' . ($startRow + 1));

    // Baris kedua header (sub-kolom untuk F-M)
    $sheet->setCellValue('F' . ($startRow + 1), 't');
    $sheet->setCellValue('G' . ($startRow + 1), 'tam');
    $sheet->setCellValue('H' . ($startRow + 1), 'pa');
    $sheet->setCellValue('I' . ($startRow + 1), 'tap');
    $sheet->setCellValue('J' . ($startRow + 1), 'kti');
    $sheet->setCellValue('K' . ($startRow + 1), 'tk');
    $sheet->setCellValue('L' . ($startRow + 1), 'tms');
    $sheet->setCellValue('M' . ($startRow + 1), 'tmk');

    // Style header
    $headerStyle = [
        'font' => ['bold' => true, 'size' => 9],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2EFDA']],
    ];
    
    // Apply style ke semua header
    $sheet->getStyle('A' . $startRow . ':O' . ($startRow + 1))->applyFromArray($headerStyle);

    // Isi data mulai dari baris setelah header
    $row = $startRow + 2;
    $no = 1;
    if (!empty($data["kedisiplinan_data"])) {
        foreach ($data["kedisiplinan_data"] as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama'] . "\nNIP. " . $item['nip']);
            $sheet->setCellValue('C' . $row, $item['pangkat'] . ' ' . $item['golongan']);
            $sheet->setCellValue('D' . $row, $item['jabatan']);
            $sheet->setCellValue('E' . $row, $item['nama_satker']);
            $sheet->setCellValue('F' . $row, $item['terlambat'] > 0 ? $item['terlambat'] : '-');
            $sheet->setCellValue('G' . $row, $item['tidak_absen_masuk'] > 0 ? $item['tidak_absen_masuk'] : '-');
            $sheet->setCellValue('H' . $row, $item['pulang_awal'] > 0 ? $item['pulang_awal'] : '-');
            $sheet->setCellValue('I' . $row, $item['tidak_absen_pulang'] > 0 ? $item['tidak_absen_pulang'] : '-');
            $sheet->setCellValue('J' . $row, $item['keluar_tidak_izin'] > 0 ? $item['keluar_tidak_izin'] : '-');
            $sheet->setCellValue('K' . $row, $item['tidak_masuk_tanpa_ket'] > 0 ? $item['tidak_masuk_tanpa_ket'] : '-');
            $sheet->setCellValue('L' . $row, $item['tidak_masuk_sakit'] > 0 ? $item['tidak_masuk_sakit'] : '-');
            $sheet->setCellValue('M' . $row, $item['tidak_masuk_kerja'] > 0 ? $item['tidak_masuk_kerja'] : '-');
            $sheet->setCellValue('N' . $row, !empty($item['bentuk_pembinaan']) ? $item['bentuk_pembinaan'] : '-');
            $sheet->setCellValue('O' . $row, !empty($item['keterangan']) ? $item['keterangan'] : '-');
            $row++;
        }
    } else {
        $sheet->mergeCells('A' . $row . ':O' . $row);
        $sheet->setCellValue('A' . $row, 'Tidak ada data');
        $sheet->getStyle('A' . $row)->getFont()->setSize(9);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $row++;
    }

    // Style tabel data
    $lastRow = $row - 1;
    $tableStyle = [
        'font' => ['size' => 9],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
    ];
    $sheet->getStyle('A' . ($startRow + 2) . ':O' . $lastRow)->applyFromArray($tableStyle);

    // Text wrapping for NAMA/NIP and PANGKAT/GOL.RUANG with center alignment
    $sheet->getStyle('B' . ($startRow + 2) . ':B' . $lastRow)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C' . ($startRow + 2) . ':C' . $lastRow)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    // Text wrapping for BENTUK PEMBINAAN with center alignment and word wrap
    $sheet->getStyle('N' . ($startRow + 2) . ':N' . $lastRow)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    // Keterangan with font size 9
    $row += 2;
    $sheet->setCellValue('A' . $row, 'KETERANGAN :');
    $sheet->getStyle('A' . $row)->getFont()->setSize(9)->setBold(true);
    $row++;
    $sheet->setCellValue('A' . $row, 't = TERLAMBAT');
    $sheet->setCellValue('F' . $row, 'kti = KELUAR KANTOR TIDAK IZIN ATASAN');
    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setSize(9);
    $row++;
    $sheet->setCellValue('A' . $row, 'tam = TIDAK ABSEN MASUK');
    $sheet->setCellValue('F' . $row, 'tk = TIDAK MASUK TANPA KETERANGAN');
    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setSize(9);
    $row++;
    $sheet->setCellValue('A' . $row, 'pa = PULANG AWAL');
    $sheet->setCellValue('F' . $row, 'tms = TIDAK MASUK KARENA SAKIT TANPA MENGAJUKAN CUTI SAKIT');
    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setSize(9);
    $row++;
    $sheet->setCellValue('A' . $row, 'tap = TIDAK ABSEN PULANG');
    $sheet->setCellValue('F' . $row, 'tmk = TIDAK MASUK KERJA');
    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setSize(9);

    // Tanda tangan with font size 9
    if ($data["tanda_tangan"]) {
        $row += 3;
        $sheet->setCellValue('K' . $row, $data["tanda_tangan"]['lokasi'] . ', ' . date('d F Y', strtotime($data["tanda_tangan"]['tanggal'])));
        $sheet->getStyle('K' . $row)->getFont()->setSize(9);
        $row++;
        $sheet->setCellValue('K' . $row, $data["tanda_tangan"]['nama_jabatan']);
        $sheet->getStyle('K' . $row)->getFont()->setSize(9);
        $row += 4; // Jarak untuk tanda tangan
        $sheet->setCellValue('K' . $row, $data["tanda_tangan"]['nama_penandatangan']);
        $sheet->getStyle('K' . $row)->getFont()->setSize(9)->setBold(true);
        $row++;
        $sheet->setCellValue('K' . $row, 'NIP. ' . $data["tanda_tangan"]['nip_penandatangan']);
        $sheet->getStyle('K' . $row)->getFont()->setSize(9);
    }

    // Penyesuaian lebar kolom (dikecilkan untuk tabel yang lebih kompak)
    $sheet->getColumnDimension('A')->setWidth(4);  // Dikecilkan dari 5
    $sheet->getColumnDimension('B')->setWidth(30);  // Dikecilkan dari 35 untuk NAMA/NIP
    $sheet->getColumnDimension('C')->setWidth(22);  // Dikecilkan dari 28 untuk PANGKAT/GOL.RUANG
    $sheet->getColumnDimension('D')->setWidth(25);  // Dikecilkan dari 30 untuk JABATAN
    $sheet->getColumnDimension('E')->setWidth(30);  // Dikecilkan dari 35 untuk SATUAN KERJA
    $sheet->getColumnDimension('F')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('G')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('H')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('I')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('J')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('K')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('L')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('M')->setWidth(8);   // Dikecilkan dari 10 untuk kolom angka
    $sheet->getColumnDimension('N')->setWidth(25);  // Dikecilkan dari 30 untuk BENTUK PEMBINAAN
    $sheet->getColumnDimension('O')->setWidth(20);  // Dikecilkan dari 25 untuk KETERANGAN

    // Tinggi baris untuk header dikurangi agar lebih kompak
    $sheet->getRowDimension($startRow)->setRowHeight(25);    // Dikurangi dari 30
    $sheet->getRowDimension($startRow + 1)->setRowHeight(25); // Dikurangi dari 30

    // Output Excel
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_Disiplin_Hakim_' . date('F_Y', mktime(0, 0, 0, $filter_bulan, 1, $filter_tahun)) . '.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}

    public function rekapBulanan()
{
    $pegawaiModel = new PegawaiModel();
    $kedisiplinanModel = new KedisiplinanModel();
    $session = session();

    // Ambil tahun unik dari database untuk dropdown
    $daftar_tahun_raw = $kedisiplinanModel->distinct()->select("tahun")
                                        ->where("created_by", $session->get("user_id"))
                                        ->orderBy("tahun", "DESC")
                                        ->findAll();
    $data["daftar_tahun"] = array_column($daftar_tahun_raw, "tahun");
    
    // Tambahkan tahun saat ini jika belum ada
    if (empty($data["daftar_tahun"])) {
        $data["daftar_tahun"][] = date("Y");
    }

    // Ambil tahun dari parameter GET, default ke tahun saat ini jika tidak ada
    $tahun_dipilih = $this->request->getVar("tahun") ?? date("Y");
    $data["tahun_dipilih"] = $tahun_dipilih;

    // Ambil data pegawai yang memiliki kedisiplinan di tahun yang dipilih
    $kedisiplinan_data_raw = $kedisiplinanModel->select("kedisiplinan.pegawai_id, kedisiplinan.bulan")
                                            ->join("pegawai", "kedisiplinan.pegawai_id = pegawai.id")
                                            ->where("kedisiplinan.created_by", $session->get("user_id"))
                                            ->where("kedisiplinan.tahun", $tahun_dipilih)
                                            ->findAll();
    
    // Ambil ID pegawai yang memiliki data kedisiplinan
    $pegawai_ids = array_unique(array_column($kedisiplinan_data_raw, "pegawai_id"));

    // Ambil data pegawai yang relevan
    $pegawai_list = [];
    if (!empty($pegawai_ids)) {
        $pegawai_list = $pegawaiModel->select("id, nama, nip, pangkat, golongan, jabatan")
                                    ->where("created_by", $session->get("user_id"))
                                    ->whereIn("id", $pegawai_ids)
                                    ->orderBy("nama", "ASC")
                                    ->findAll();
    }

    // Strukturkan data rekap_bulanan
    $rekap_bulanan = [];
    foreach ($pegawai_list as $pegawai) {
        $rekap_bulanan[] = [
            "pegawai" => [
                "nama" => $pegawai["nama"],
                "nip" => $pegawai["nip"],
                "pangkat" => $pegawai["pangkat"],
                "golongan" => $pegawai["golongan"],
                "jabatan" => $pegawai["jabatan"],
                "id" => $pegawai["id"]
            ],
            "kedisiplinan" => []
        ];
    }

    // Isi data kedisiplinan untuk setiap pegawai
    foreach ($kedisiplinan_data_raw as $row) {
        foreach ($rekap_bulanan as &$rekap) {
            if ($rekap["pegawai"]["id"] == $row["pegawai_id"]) {
                $rekap["kedisiplinan"][] = ["bulan" => $row["bulan"]];
            }
        }
    }

    $data["rekap_bulanan"] = $rekap_bulanan;
    $data["nama_bulan"] = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    echo view("user/rekap_bulanan", $data);
}

    public function uploadFile()
    {
        $laporanFileModel = new LaporanFileModel();
        $session = session();

        $data["laporan_data"] = $laporanFileModel->where("created_by", $session->get("user_id"))->orderBy("created_at", "DESC")->findAll();

        echo view("user/upload_file", $data);
    }

    public function addFile()
    {
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $rules = [
            "nama_laporan" => "required",
            "bulan"        => "required|numeric",
            "tahun"        => "required|numeric",
            "keterangan"   => "permit_empty",
            "files"        => "uploaded[files]|max_size[files,5120]|ext_in[files,jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx]"
        ];

        if ($this->validate($rules)) {
            $files = $this->request->getFiles();
            $uploaded_files_count = 0;
            $max_files = 5;

            foreach ($files["files"] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    if ($uploaded_files_count >= $max_files) {
                        $session->setFlashdata("msg", "Maksimum 5 file dapat diupload sekaligus.");
                        $session->setFlashdata("msg_type", "danger");
                        return redirect()->to(base_url("user/upload_file"));
                    }

                    $newName = $file->getRandomName();
                    $upload_dir = WRITEPATH . "uploads/";

                    // Pastikan direktori uploads ada
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $file->move($upload_dir, $newName);

                    // Check if a report with the same name, month, year, and user exists
                    $nama_laporan = $this->request->getVar("nama_laporan");
                    $bulan = $this->request->getVar("bulan");
                    $tahun = $this->request->getVar("tahun");
                    $keterangan = $this->request->getVar("keterangan");
                    $created_by = $session->get("user_id");

                    $existing_report = $laporanFileModel->where([
                        "nama_laporan" => $nama_laporan,
                        "bulan"        => $bulan,
                        "tahun"        => $tahun,
                        "created_by"   => $created_by,
                        "file_path"    => $newName // Check with new file name as well
                    ])->orderBy("created_at", "DESC")->first();

                    if ($existing_report) {
                        if ($existing_report["status"] == "ditolak") {
                            // If rejected, update the existing record
                            $laporanFileModel->update($existing_report["id"], [
                                "file_path"  => $newName,
                                "keterangan" => $keterangan,
                                "status"     => "terkirim",
                                "feedback"   => NULL,
                                "created_at" => date("Y-m-d H:i:s"),
                            ]);

                            // Delete old file if exists
                            if (!empty($existing_report["file_path"]) && file_exists($upload_dir . $existing_report["file_path"])) {
                                unlink($upload_dir . $existing_report["file_path"]);
                            }

                            // Create notification for admin
                            $adminUsers = (new \App\Models\UserModel())->where("role", "admin")->findAll();
                            foreach ($adminUsers as $admin) {
                                $notifikasiModel->save([
                                    "user_id" => $admin["id"],
                                    "judul"   => "Laporan Dikirim Ulang",
                                    "pesan"   => "Laporan ditolak sebelumnya \"" . $nama_laporan . "\" telah dikirim ulang oleh " . $session->get("nama_lengkap") . ".",
                                    "tipe"    => "laporan",
                                    "id_referensi" => $existing_report["id"],
                                ]);
                            }
                            $session->setFlashdata("msg", "Laporan berhasil di-reupload.");
                            $session->setFlashdata("msg_type", "success");
                        } else {
                            unlink($upload_dir . $newName); // Delete newly uploaded file
                            $session->setFlashdata("msg", "Laporan dengan nama, bulan, dan tahun yang sama sudah ada dan statusnya bukan ditolak.");
                            $session->setFlashdata("msg_type", "warning");
                        }
                    } else {
                        // Insert new record
                        $laporanFileModel->save([
                            "nama_laporan" => $nama_laporan,
                            "bulan"        => $bulan,
                            "tahun"        => $tahun,
                            "file_path"    => $newName,
                            "keterangan"   => $keterangan,
                            "status"       => "terkirim",
                            "created_by"   => $created_by,
                        ]);

                        // Create notification for admin
                        $adminUsers = (new \App\Models\UserModel())->where("role", "admin")->findAll();
                        foreach ($adminUsers as $admin) {
                            $notifikasiModel->save([
                                "user_id" => $admin["id"],
                                "judul"   => "Laporan Baru",
                                "pesan"   => "Laporan baru \"" . $nama_laporan . "\" telah dikirim oleh " . $session->get("nama_lengkap") . ".",
                                "tipe"    => "laporan",
                                "id_referensi" => $laporanFileModel->getInsertID(),
                            ]);
                        }
                        $session->setFlashdata("msg", "File berhasil diupload.");
                        $session->setFlashdata("msg_type", "success");
                    }
                    $uploaded_files_count++;
                }
            }
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/upload_file"));
    }

    public function getFile($filePath = null)
{
    $session = session();
    if (!$session->get("isLoggedIn") || $session->get("role") !== "user") {
        return redirect()->to(base_url("login"));
    }

    if ($filePath) {
        $filePath = urldecode($filePath); // Decode URL-encoded path
        $uploadDir = WRITEPATH . "uploads/" . $filePath;

        if (file_exists($uploadDir)) {
            // Tentukan tipe konten berdasarkan ekstensi file
            $mimeType = mime_content_type($uploadDir);
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
            readfile($uploadDir);
            exit;
        }
    }

    return $this->response->setStatusCode(404)->setBody('File tidak ditemukan.');
}

    public function reuploadFile()
    {
        $laporanFileModel = new LaporanFileModel();
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $laporan_id = $this->request->getVar("laporan_id");
        $nama_laporan = $this->request->getVar("nama_laporan");
        $bulan = $this->request->getVar("bulan");
        $tahun = $this->request->getVar("tahun");
        $keterangan = $this->request->getVar("keterangan");

        $rules = [
            "laporan_id" => "required|numeric",
            "files"      => "uploaded[files]|max_size[files,5120]|ext_in[files,jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx]"
        ];

        if ($this->validate($rules)) {
            $file = $this->request->getFile("files");

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $upload_dir = WRITEPATH . "uploads/";

                // Pastikan direktori uploads ada
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file->move($upload_dir, $newName);

                $existing_report = $laporanFileModel->find($laporan_id);

                if ($existing_report && $existing_report["created_by"] == $session->get("user_id")) {
                    // Delete old file if exists
                    if (!empty($existing_report["file_path"]) && file_exists($upload_dir . $existing_report["file_path"])) {
                        unlink($upload_dir . $existing_report["file_path"]);
                    }

                    $laporanFileModel->update($laporan_id, [
                        "file_path"  => $newName,
                        "keterangan" => $keterangan,
                        "status"     => "terkirim",
                        "feedback"   => NULL,
                        "created_at" => date("Y-m-d H:i:s"),
                    ]);

                    // Create notification for admin
                    $adminUsers = (new \App\Models\UserModel())->where("role", "admin")->findAll();
                    foreach ($adminUsers as $admin) {
                        $notifikasiModel->save([
                            "user_id" => $admin["id"],
                            "judul"   => "Laporan Dikirim Ulang",
                            "pesan"   => "Laporan ditolak sebelumnya \"" . $nama_laporan . "\" telah dikirim ulang oleh " . $session->get("nama_lengkap") . ".",
                            "tipe"    => "laporan",
                            "id_referensi" => $laporan_id,
                        ]);
                    }
                    $session->setFlashdata("msg", "Laporan berhasil di-reupload.");
                    $session->setFlashdata("msg_type", "success");
                } else {
                    unlink($upload_dir . $newName); // Delete newly uploaded file
                    $session->setFlashdata("msg", "Laporan tidak ditemukan atau Anda tidak memiliki izin untuk meng-reupload.");
                    $session->setFlashdata("msg_type", "danger");
                }
            } else {
                $session->setFlashdata("msg", "Gagal mengupload file.");
                $session->setFlashdata("msg_type", "danger");
            }
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/upload_file"));
    }

    public function deleteFile()
{
    $laporanFileModel = new LaporanFileModel();
    $session = session();

    $laporan_id = $this->request->getPost("laporan_id_to_delete");

    if (!$laporan_id) {
        $session->setFlashdata("msg", "ID laporan tidak ditemukan.");
        $session->setFlashdata("msg_type", "danger");
    } else {
        $laporan = $laporanFileModel->find($laporan_id);

        if ($laporan === null) {
            $session->setFlashdata("msg", "Laporan tidak ditemukan.");
            $session->setFlashdata("msg_type", "danger");
        } elseif (!isset($laporan["created_by"]) || $laporan["created_by"] != $session->get("user_id")) {
            $session->setFlashdata("msg", "Anda tidak memiliki izin untuk menghapus laporan ini.");
            $session->setFlashdata("msg_type", "danger");
        } else {
            $upload_dir = WRITEPATH . "uploads/";
            if (!empty($laporan["file_path"]) && file_exists($upload_dir . $laporan["file_path"])) {
                unlink($upload_dir . $laporan["file_path"]);
            }
            $laporanFileModel->delete($laporan_id);
            $session->setFlashdata("msg", "Laporan berhasil dihapus.");
            $session->setFlashdata("msg_type", "success");
        }
    }
    return redirect()->to(base_url("user/upload_file"));
}

    public function notifikasi()
    {
        $notifikasiModel = new NotifikasiModel();
        $session = session();

        $data["notifikasi_list"] = $notifikasiModel->where("user_id", $session->get("user_id"))->orderBy("created_at", "DESC")->findAll();
        
        // Tandai semua notifikasi sebagai sudah dibaca saat halaman diakses
        $notifikasiModel->where("user_id", $session->get("user_id"))->set(["is_read" => 1])->update();
        $session->set("notif_count", 0); // Update session notif_count

        echo view("user/notifikasi", $data);
    }

    public function profil()
    {
        $userModel = new \App\Models\UserModel();
        $session = session();

        $data["user_data"] = $userModel->find($session->get("user_id"));

        echo view("user/profil", $data);
    }

        public function updateProfil()
    {
        $userModel = new \App\Models\UserModel();
        $session = session();

        $user_id = $session->get("user_id");
        $nama_lengkap = $this->request->getVar("nama_lengkap");
        $email = $this->request->getVar("email");
        $username = $this->request->getVar("username");
        $password_lama = $this->request->getVar("password_lama");
        $password_baru = $this->request->getVar("password_baru"); // Sesuaikan dengan nama field di form
        $konfirmasi_password = $this->request->getVar("konfirmasi_password");
        $tab = $this->request->getVar("tab");

        // Debugging: Tampilkan data yang diterima
        // var_dump($this->request->getVar()); exit;

        $rules = [];

        // Validasi untuk tab Profil
        if ($tab === "profile" || ($tab === null && ($nama_lengkap !== null || $email !== null || $username !== null))) {
            $rules = [
                "nama_lengkap" => "required",
                "email" => "required|valid_email|is_unique[users.email,id,{$user_id}]",
                "username" => "required|is_unique[users.username,id,{$user_id}]",
            ];
        }

        // Validasi untuk tab Password
        if ($tab === "password" || ($tab === null && ($password_lama !== null || $password_baru !== null || $konfirmasi_password !== null))) {
            $rules = [
                "password_lama" => "required",
                "password_baru" => "required|min_length[6]", // Sesuaikan dengan nama field
                "konfirmasi_password" => "required|matches[password_baru]", // Sesuaikan dengan nama field
            ];
        }

        if (empty($rules)) {
            $session->setFlashdata("msg", "Tidak ada data yang valid untuk diproses.");
            $session->setFlashdata("msg_type", "danger");
            return redirect()->to(base_url("user/profil"));
        }

        if ($this->validate($rules)) {
            $data = [];

            // Update data profil jika dari tab Profil
            if ($tab === "profile" || ($tab === null && ($nama_lengkap !== null || $email !== null || $username !== null))) {
                $data = array_merge($data, [
                    "nama_lengkap" => $nama_lengkap,
                    "email" => $email,
                    "username" => $username,
                ]);
            }

            // Update password jika dari tab Password
            if ($tab === "password" || ($tab === null && $password_baru !== null)) {
                $user = $userModel->find($user_id);
                if (password_verify($password_lama, $user['password'])) {
                    $data["password"] = password_hash($password_baru, PASSWORD_DEFAULT); // Gunakan password_baru
                } else {
                    $session->setFlashdata("msg", "Password lama tidak sesuai.");
                    $session->setFlashdata("msg_type", "danger");
                    return redirect()->to(base_url("user/profil"));
                }
            }

            if (!empty($data)) {
                $userModel->update($user_id, $data);
                $session->setFlashdata("msg", "Profil berhasil diperbarui.");
                $session->setFlashdata("msg_type", "success");

                // Update session data
                if (isset($nama_lengkap)) $session->set("nama_lengkap", $nama_lengkap);
                if (isset($email)) $session->set("email", $email);
                if (isset($username)) $session->set("username", $username);
            } else {
                $session->setFlashdata("msg", "Tidak ada perubahan yang disimpan.");
                $session->setFlashdata("msg_type", "warning");
            }
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/profil"));
    }

    public function updateFotoProfil()
    {
        $userModel = new \App\Models\UserModel();
        $session = session();

        $user_id = $session->get("user_id");

        $rules = [
            "foto_profil" => "uploaded[foto_profil]|max_size[foto_profil,1024]|ext_in[foto_profil,jpg,jpeg,png,gif]",
        ];

        if ($this->validate($rules)) {
            $file = $this->request->getFile("foto_profil");

            if ($file->isValid() && !$file->hasMoved()) {
                $old_photo = $userModel->find($user_id)["foto_profil"];
                $upload_dir = FCPATH . "assets/img/";

                // Delete old photo if not default
                if (!empty($old_photo) && $old_photo != "default.png" && file_exists($upload_dir . $old_photo)) {
                    unlink($upload_dir . $old_photo);
                }

                $newName = $file->getRandomName();
                $file->move($upload_dir, $newName);

                $userModel->update($user_id, [
                    "foto_profil" => $newName,
                ]);

                $session->setFlashdata("msg", "Foto profil berhasil diperbarui.");
                $session->setFlashdata("msg_type", "success");
                $session->set("foto_profil", $newName);
            } else {
                $session->setFlashdata("msg", "Gagal mengupload foto profil.");
                $session->setFlashdata("msg_type", "danger");
            }
        } else {
            $session->setFlashdata("msg", $this->validator->listErrors());
            $session->setFlashdata("msg_type", "danger");
        }
        return redirect()->to(base_url("user/profil"));
    }
}


