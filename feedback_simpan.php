<?php
    include "koneksi.php";

    $token    = $_POST['token'] ?? '';
    $rating   = $_POST['rating'] ?? '';
    $komentar = $_POST['komentar'] ?? null;
    $anonim   = isset($_POST['anonim']) ? 1 : 0;

    if ($token === '' || $rating === '') {
        die("Data tidak lengkap");
    }

    $stmt = $conn->prepare("
        SELECT id, nama, feedback_status, kdsatker
        FROM tamu
        WHERE feedback_token = ?
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        die("Token tidak valid");
    }

    $tamu = $res->fetch_assoc();

    $kdsatker = $tamu['kdsatker'] ?? '694762';


    if ($tamu['feedback_status'] === 'done') {
        die("Feedback sudah pernah diisi");
    }

    $nama = ($anonim === 1) ? null : $tamu['nama'];

    $stmt = $conn->prepare("
        INSERT INTO feedback (nama, rating, komentar, anonim, kdsatker)
        VALUES (?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sssis",
        $nama,
        $rating,
        $komentar,
        $anonim,
        $kdsatker
    );

    $stmt->execute();

    $stmt = $conn->prepare("
        UPDATE tamu 
        SET feedback_status='done', feedback_at=NOW()
        WHERE id=?
    ");
    $stmt->bind_param("i", $tamu['id']);
    $stmt->execute();

    echo "OK";
?>