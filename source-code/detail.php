<?php
    include "koneksi.php";
    $id = intval($_GET['id'] ?? 0);
    $kdsatker = $_GET['kdsatker'] ?? '694762';

    $stmt = $conn->prepare("
        SELECT * FROM tamu
        WHERE id = ? AND kdsatker = ?
    ");
    $stmt->bind_param("is", $id, $kdsatker);
    $stmt->execute();
    $dt = $stmt->get_result()->fetch_assoc();

    if (!$dt) {
        die("Data tidak ditemukan");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{
            box-sizing:border-box;
        }
        html{
            min-height:100%;
            background:linear-gradient(180deg,#2a6099 0%, #0f7a6f 100%);
        }
        body{
            min-height:100vh;
            margin:0;
            font-family:'Poppins',sans-serif;
            background:transparent;
            color:#1b1b1b;
        }
        .page{
            max-width:1400px;
            margin-inline:auto;
            padding-inline:clamp(16px,4vw,60px);
        }
        .header { 
            background: #23446b; 
            padding: 20px; 
            color: white; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow:0 4px 8px rgba(18,34,50,0.35); 
        }
        .header-detail{
            max-width:1100px;
            margin:0 auto;
            padding:0 5px;
            display:flex;
            align-items:center;
            gap:16px;
            color:#fff;
        }
        .header-detail a{
            color:#fff;
            font-size:clamp(18px,3vw,26px);
            text-decoration:none;
        }
        .detail-box{
            background:#fff;
            border-radius:14px;
            padding:clamp(16px,3vw,28px);
            max-width:1100px;
            margin-inline:auto;
            display:flex;
            gap:clamp(14px,3vw,28px);
            box-shadow:0 18px 40px rgba(4,23,43,.18);
        }
        .foto{
            flex:0 0 clamp(120px,18vw,160px);
        }
        .foto img{
            width:100%;
            aspect-ratio:3/4;
            object-fit:cover;
            border-radius:12px;
            background:#ededed;
        }
        .grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:clamp(14px,3vw,36px);
            width:100%;
        }
        label{
            font-weight:600;
            color:#1f3b63;
            font-size:clamp(12px,1.8vw,15px);
        }
        input,
        textarea{
            width:100%;
            border-radius:10px;
            border:1px solid rgba(18,34,50,.08);
            padding:clamp(8px,1.8vw,12px);
            background:#f8f8f8;
            font-family:"Poppins",sans-serif;
            font-size:clamp(11px,1.6vw,13px);
            box-shadow:0 4px 6px rgba(0,0,0,.08);
            outline:none;
            margin-top:4px;
            margin-bottom: 10px;
        }
        input{
            height:clamp(36px,5vw,45px);
        }
        textarea{
            height:clamp(110px,12vw,125px);
            resize:none;
        }
        .left,
        .right{
            flex-direction:column;
            gap:10px;
        }
    </style>
</head>
<body>
    <div class="header"> </div>
    <div class="header-detail">
        <a href="dashboard.php?kdsatker=<?= $kdsatker ?>"><i class="fa-solid fa-arrow-left"></i></a>
        <h2>Detail Tamu</h2>
    </div>

    <div class="page">
    <div class="detail-box">
        <div class="foto">
            <img src="uploads/<?= $dt['foto'] ?>" onerror="this.src='camera.png'">
        </div>
        <div class="grid">
            <div class="left">
                <div>
                    <label>Tanggal Kedatangan</label>
                    <input value="<?= date("d F Y", strtotime($dt['tanggal'])) ?>" readonly>
                </div>
                <div>
                    <label>Nama</label>
                    <input value="<?= $dt['nama'] ?>" readonly>
                </div>
                <div>
                    <label>No. Handphone</label>
                    <input value="<?= $dt['nohp'] ?>" readonly>
                </div>
                <div>
                    <label>Email</label>
                    <input value="<?= $dt['email'] ?>" readonly>
                </div>
            </div>
            <div>
                <div class="right">
                    <label>Instansi Asal/Alamat</label>
                    <input value="<?= $dt['instansi'] ?>" readonly>
                </div>
                <div>
                    <label>Yang Ingin Ditemui</label>
                    <input value="<?= $dt['tujuan'] ?>" readonly>
                </div>
                <div>
                    <label>Keperluan</label>
                    <textarea readonly><?= $dt['keperluan'] ?></textarea>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
