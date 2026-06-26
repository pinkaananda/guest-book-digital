<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Shared\Date;

    $conn = new mysqli("localhost", "root", "", "bukutamu");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    date_default_timezone_set("Asia/Jakarta");
    $kdsatker = $_POST['kdsatker']
         ?? $_GET['kdsatker']
         ?? '694762';

    if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
        header("Location: import.php?status=error");
        exit;
    }

    $file = $_FILES['file_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

    $header = array_map(function($h){
        return strtolower(trim($h));
    }, $rows[0]);

    $map = array_flip($header);

    $required = ['nama'];
    foreach ($required as $col) {
        if (!isset($map[$col])) {
            throw new Exception("Kolom '$col' tidak ditemukan di Excel");
        }
    }

        $stmt = $conn->prepare("
            INSERT INTO tamu
            (tanggal, nama, email, instansi, nohp, tujuan, keperluan, feedback_token, feedback_status, kdsatker)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");

        if (!$stmt) {
            throw new Exception("Prepare gagal");
        }

        foreach ($rows as $i => $row) {

        if ($i === 0) continue;

        $cellTanggal = $row[$map['tanggal']] ?? null;

        if ($cellTanggal) {
            if (is_numeric($cellTanggal)) {
                $tanggal = Date::excelToDateTimeObject($cellTanggal)->format('Y-m-d');
            } else {
                $tanggal = date('Y-m-d', strtotime($cellTanggal));
            }
        } else {
            $tanggal = date('Y-m-d');
        }

        if (!$tanggal || $tanggal === '1970-01-01') {
            $tanggal = date('Y-m-d');
        }

        $nama = trim($row[$map['nama']] ?? '');
        if ($nama === '') continue;

        $email     = trim($row[$map['email']] ?? '') ?: null;
        $instansi  = trim($row[$map['instansi']] ?? '') ?: null;

        $nohpRaw = $row[$map['nohp']] ?? '';
        $nohp = preg_replace('/\D/', '', $nohpRaw);
        if ($nohp && substr($nohp, 0, 1) === '0') {
            $nohp = '62' . substr($nohp, 1);
        }
        if ($nohp === '') $nohp = null;

        $tujuan    = trim($row[$map['tujuan']] ?? '') ?: null;
        $keperluan = trim($row[$map['keperluan']] ?? '') ?: null;

        $token  = bin2hex(random_bytes(16));
        $status = null;

        $stmt->bind_param(
            "sssssssss",
            $tanggal,
            $nama,
            $email,
            $instansi,
            $nohp,
            $tujuan,
            $keperluan,
            $token,
            $status,
            $kdsatker
        );

        $stmt->execute();
    }

        $stmt->close();
        $conn->close();

        header("Location: import.php?status=success");
        exit;

    } catch (Exception $e) {
        header("Location: import.php?status=error");
        exit;
    }
?>