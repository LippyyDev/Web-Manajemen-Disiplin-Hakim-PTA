<?php
namespace App\Libraries;

require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

class PDF extends \TCPDF {
    public $current_bulan;
    public $current_tahun;
    public $current_satker_name;
    public $column_widths;
    public $header_height1 = 12;
    public $header_height2 = 6;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        parent::__construct($orientation, $unit, $format);
        // Definisikan lebar kolom di konstruktor agar tersedia di seluruh metode
        $this->column_widths = [8, 45, 25, 30, 30, 10, 10, 10, 10, 10, 10, 10, 10, 35, 15];
        $this->SetMargins(10, 35, 10); // Pastikan margin kiri 10
    }

    public function Header() {
        try {
            $this->Image(FCPATH . 'assets/img/logo.png', 10, 8, 15);
        } catch (Exception $e) {
            error_log('TCPDF Error: Logo image not found or invalid.');
        }
        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(10);
        $this->Cell(0, 5, 'LAPORAN DISIPLIN HAKIM', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(0, 5, 'YANG TIDAK MEMATUHI KETENTUAN JAM KERJA SESUAI DENGAN PERMA NO 7 TAHUN 2016', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 9);

        // Pengecekan dan konversi $current_bulan
        $bulan = isset($this->current_bulan) && is_numeric($this->current_bulan) ? (int)$this->current_bulan : null;
        if ($bulan === null) {
            $bulan = date('n'); // Fallback ke bulan saat ini jika gagal
        }
        $bulan_name = $bulan && $bulan >= 1 && $bulan <= 12 ? strtoupper(getBulanIndo($bulan)) : 'Tidak Diketahui';
        $tahun = $this->current_tahun ?? date('Y');
        $this->Cell(0, 5, 'BULAN: ' . $bulan_name . ' ' . $tahun, 0, 1, 'C');

        if (!empty($this->current_satker_name)) {
            $this->Cell(0, 5, 'SATKER: ' . strtoupper($this->current_satker_name), 0, 1, 'C');
        }
        $this->Ln(2);
    }

    public function Footer() {
        $this->SetY(-10);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 5, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }

    public function drawComplexHeader() {
        $this->SetFont('helvetica', 'B', 7);
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.2);

        $x_start = $this->GetX();
        $y_start = $this->GetY();
        $total_header_height = $this->header_height1 + $this->header_height2;

        $span_headers = [
            ["NO", $this->column_widths[0]],
            ["NAMA/NIP", $this->column_widths[1]],
            ["PANGKAT/\nGOL. RUANG", $this->column_widths[2]],
            ["JABATAN", $this->column_widths[3]],
            ["SATUAN KERJA", $this->column_widths[4]],
            ["BENTUK\nPEMBINAAN", $this->column_widths[13]],
            ["KET", $this->column_widths[14]],
        ];

        $current_x = $x_start;
        for ($i = 0; $i < 5; $i++) {
            $this->MultiCell($span_headers[$i][1], $total_header_height, $span_headers[$i][0], 1, 'C', true, 0, $current_x, $y_start, true, 0, false, true, $total_header_height, 'M');
            $current_x += $span_headers[$i][1];
        }

        $uraian_width = array_sum(array_slice($this->column_widths, 5, 8));
        $uraian_x = $current_x;
        $this->MultiCell($uraian_width, $this->header_height1, "URAIAN AKUMULASI TIDAK\nDIPATUHKAN", 1, 'C', true, 0, $uraian_x, $y_start, true, 0, false, true, $this->header_height1, 'M');

        $subheader_y = $y_start + $this->header_height1;
        $subheaders = ["t", "kti", "tam", "tk", "pa", "tms", "tap", "tmk"];
        $current_x_sub = $uraian_x;
        for ($i = 0; $i < 8; $i++) {
            $sub_width = $this->column_widths[5 + $i];
            $this->MultiCell($sub_width, $this->header_height2, $subheaders[$i], 1, 'C', true, 0, $current_x_sub, $subheader_y, true, 0, false, true, $this->header_height2, 'M');
            $current_x_sub += $sub_width;
        }

        $current_x = $uraian_x + $uraian_width;
        for ($i = 5; $i < count($span_headers); $i++) {
            $this->MultiCell($span_headers[$i][1], $total_header_height, $span_headers[$i][0], 1, 'C', true, 0, $current_x, $y_start, true, 0, false, true, $total_header_height, 'M');
            $current_x += $span_headers[$i][1];
        }

        $this->SetY($y_start + $total_header_height);
    }

    public function drawTableRow($data, $row_height = 10) {
        $this->SetFont('helvetica', '', 7);
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.2);

        $start_x = $this->getMargins()['left'];
        $y_start = $this->GetY();

        for ($i = 0; $i < count($data); $i++) {
            $width = $this->column_widths[$i];
            $content = $data[$i];
            // Set alignment based on column index
            $align = 'L'; // Default to left alignment
            if ($i == 0 || ($i >= 5 && $i <= 12) || $i == 13 || $i == 14) {
                $align = 'C'; // Center alignment for NO, numeric columns (5-12), BENTUK PEMBINAAN (13), and KET (14)
            }
            $this->MultiCell($width, $row_height, $content, 1, $align, true, 0, '', '', true, 0, false, true, $row_height, 'M');
        }

        $this->SetY($y_start + $row_height);
    }

    public function drawKeterangan() {
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 5, 'KETERANGAN:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 7);

        $keterangan = [
            ['t = TERLAMBAT', 'kti = KELUAR KANTOR TIDAK IZIN ATASAN'],
            ['tam = TIDAK ABSEN MASUK', 'tk = TIDAK MASUK TANPA KETERANGAN'],
            ['pa = PULANG AWAL', 'tms = TIDAK MASUK KARENA SAKIT TANPA MENGAJUKAN CUTI SAKIT'],
            ['tap = TIDAK ABSEN PULANG', 'tmk = TIDAK MASUK KERJA']
        ];

        $col_width = ($this->getPageWidth() - $this->getMargins()['left'] - $this->getMargins()['right']) / 2;
        foreach ($keterangan as $ket) {
            $y_before = $this->GetY();
            $this->MultiCell($col_width, 4, $ket[0], 0, 'L', false, 0, $this->getMargins()['left'], $y_before);
            $y_after1 = $this->GetY();
            $this->MultiCell($col_width, 4, $ket[1], 0, 'L', false, 1, $this->getMargins()['left'] + $col_width, $y_before);
            $y_after2 = $this->GetY();
            $this->SetY(max($y_after1, $y_after2));
        }
    }

    public function drawTandaTangan($tanda_tangan) {
    if ($tanda_tangan) {
        // Cek posisi Y saat ini dan atur posisi tanda tangan 20mm dari bawah
        $bottom_margin = $this->getBreakMargin();
        $current_y = $this->GetY();
        $desired_y = $this->getPageHeight() - $bottom_margin - 35; // 30mm dari bawah

        if ($current_y < $desired_y) {
            $this->SetY($desired_y);
        } else {
            $this->SetY(-20); // Jika sudah dekat bawah, pakai offset dari bawah
        }

        $this->SetFont('helvetica', '', 9);
        $ttd_width = 80;
        $left_margin_ttd = $this->getPageWidth() - $this->getMargins()['right'] - $ttd_width;

        $this->SetX($left_margin_ttd);
        $this->Cell($ttd_width, 5, $tanda_tangan['lokasi'] . ', ' . date('d F Y', strtotime($tanda_tangan['tanggal'])), 0, 1, 'C');

        $this->SetX($left_margin_ttd);
        $this->Cell($ttd_width, 5, $tanda_tangan['nama_jabatan'], 0, 1, 'C');

        $this->Ln(15);

        $this->SetX($left_margin_ttd);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell($ttd_width, 5, $tanda_tangan['nama_penandatangan'], 0, 1, 'C');

        $this->SetFont('helvetica', '', 9);
        $this->SetX($left_margin_ttd);
        $this->Cell($ttd_width, 5, 'NIP. ' . $tanda_tangan['nip_penandatangan'], 0, 1, 'C');
    } else {
        $this->Ln(10);
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 5, 'Tanda tangan belum tersedia.', 0, 1, 'C');
    }
}
}