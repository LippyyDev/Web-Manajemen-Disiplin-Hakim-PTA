<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .keterangan {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2 class="header">LAPORAN DISIPLIN HAKIM</h2>
    <h3 class="header">YANG TIDAK MEMATUHI KETENTUAN JAM KERJA SESUAI DENGAN PERMA NO 7 TAHUN 2016</h3>
    <p class="header">BULAN: <?= getBulanIndo($filter_bulan) ?> <?= $filter_tahun ?></p>
    <p class="header">TAHUN: <?= $filter_tahun ?></p>
    <p class="header">SATKER: <?= $filter_satker_name ?></p>

    <table>
        <tr>
            <th>NO</th>
            <th>NAMA/NIP</th>
            <th>PANGKAT/GOL. RUANG</th>
            <th>JABATAN</th>
            <th>SATUAN KERJA</th>
            <th colspan="8">URAIAN AKUMULASI TIDAK DIPATUHKAN</th>
            <th>BENTUK PEMBINAAN</th>
            <th>KETERANGAN</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>t=</th>
            <th>kti=</th>
            <th>tam=</th>
            <th>tk=</th>
            <th>pa=</th>
            <th>tms=</th>
            <th>tap=</th>
            <th>tmk=</th>
            <th></th>
            <th></th>
        </tr>
        <?php if (!empty($kedisiplinan_data)): ?>
            <?php $no = 1; foreach ($kedisiplinan_data as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['nama'] ?>/<?= $item['nip'] ?></td>
                    <td><?= $item['pangkat'] ?>/<?= $item['golongan'] ?></td>
                    <td><?= $item['jabatan'] ?></td>
                    <td><?= $item['nama_satker'] ?></td>
                    <td><?= $item['terlambat'] ?></td>
                    <td><?= $item['keluar_tidak_izin'] ?></td>
                    <td><?= $item['tidak_absen_masuk'] ?></td>
                    <td><?= $item['tidak_masuk_tanpa_ket'] ?></td>
                    <td><?= $item['pulang_awal'] ?></td>
                    <td><?= $item['tidak_masuk_sakit'] ?></td>
                    <td><?= $item['tidak_absen_pulang'] ?></td>
                    <td><?= $item['tidak_masuk_kerja'] ?></td>
                    <td><?= $item['bentuk_pembinaan'] ?></td>
                    <td><?= $item['keterangan'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="15">Tidak ada data</td></tr>
        <?php endif; ?>
    </table>

    <div class="keterangan">
        <h4>KETERANGAN:</h4>
        <p>t = TERLAMBAT, kti = KELUAR KANTOR TIDAK IZIN ATASAN, tam = TIDAK ABSEN MASUK, tk = TIDAK MASUK TANPA KETERANGAN</p>
        <p>pa = PULANG AWAL, tms = TIDAK MASUK KARENA SAKIT TANPA MENGAJUKAN CUTI SAKIT, tap = TIDAK ABSEN PULANG, tmk = TIDAK MASUK KERJA</p>
    </div>

    <?php if ($tanda_tangan): ?>
        <p><?= $tanda_tangan['lokasi'] ?>, <?= date('d F Y', strtotime($tanda_tangan['tanggal'])) ?></p>
        <p><?= $tanda_tangan['nama_jabatan'] ?></p>
        <br><br>
        <p><?= $tanda_tangan['nama_penandatangan'] ?></p>
        <p>NIP. <?= $tanda_tangan['nip_penandatangan'] ?></p>
    <?php else: ?>
        <p>Tanda tangan belum tersedia.</p>
    <?php endif; ?>
</body>
</html>