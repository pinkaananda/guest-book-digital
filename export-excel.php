<?php
    require 'koneksi.php';
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    date_default_timezone_set("Asia/Jakarta");

    $tipe = $_GET['tipe'] ?? 'semua';
    $id_satker = $_GET['id_satker'] ?? null;

    $where = "1=1";
    $params = [];
    $types = "";

    if ($id_satker){
        $where .= " AND id_satker = ?";
        $types .= "s";
        $params[] = $id_satker;
    }

    if ($tipe === 'tanggal'){
        $where .= " AND tanggal = ?";
        $types .= "s";
        $params[] = $_GET['tanggal'];
    }

    if ($tipe === 'bulan'){
        $where .= " AND DATE_FORMAT(tanggal,'%Y-%m') = ?";
        $types .= "s";
        $params[] = $_GET['bulan'];
    }

    $stmt = $conn->prepare("SELECT tanggal,nama,instansi,nohp,email, keperluan, tujuan FROM tamu WHERE $where ORDER BY tanggal ASC");
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray(
        ['Tanggal','Nama','Instansi Asal/Alamat','No HP','Email','Keperluan', 'Yang Ingin Ditemui'],
        NULL,'A1'
    );

    $rowNum = 2;
    while($r = $res->fetch_assoc()){
        $sheet->fromArray([
            $r['tanggal'],
            $r['nama'],
            $r['instansi'],
            $r['nohp'],
            $r['email'],
            $r['keperluan'],
            $r['tujuan'],
        ],NULL,'A'.$rowNum++);
    }

    $filename = "Data_Tamu_".date('Ymd_His').".xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit;
?>