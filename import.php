<?php
    $kdsatker = $_GET['kdsatker'] ?? '694762';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Data Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{
            box-sizing:border-box
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

        .header{
            height:70px;
            background:#23446b;
            box-shadow:0 4px 10px rgba(0,0,0,.25);
        }
        .page{
            max-width:1400px;
            margin:0 auto;
            padding:32px 32px 80px;
        }
        .page-title{
            max-width:1100px;
            margin:24px auto 20px;
            padding:0 5px;
            display:flex;
            align-items:center;
            gap:16px;
            color:#fff;
        }
        .page-title a{
            color:#fff;
            font-size:26px;
            text-decoration:none;
        }
        .page-title h1{
            margin:0;
            font-size:34px;
            font-weight:700;
        }

        .wrapper{
            max-width:1100px;
            margin:auto;
            padding:0 20px 40px;
        }
        .card{
            background:#fff;
            border-radius:10px;
            box-shadow:0 10px 25px rgba(0,0,0,.18);
        }
        .card-header{
            background:#23446b;
            color:#fff;
            padding:22px 26px;
            margin:0 -1px;
            border-radius:10px 10px 0 0;
        }
        .card-header h3{
            margin:0;
            font-size:22px;
        }
        .card-header p{
            margin:6px 0 0;
            font-size:14px;
            opacity:.9;
        }
        .card-body{
            padding:30px 26px 40px;
        }
        .file-label{
            display:flex;
            align-items:center;
            gap:10px;
            font-weight:600;
            margin-bottom:10px;
        }
        .excel-icon{
            color:#1D6F42;
            font-size:18px;
        }
        .file-input{
            position:relative;
            display:flex;
            align-items:center;
            justify-content:space-between;
            background:#f6f6f6;
            border-radius:8px;
            padding:8px 12px;
            height:42px;
        }
        .file-placeholder{
            font-size:14px;
            color:#888;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .file-btn{
            background:#e9ecef;
            border:none;
            padding:6px 14px;
            border-radius:6px;
            font-size:13px;
            font-weight:500;
            cursor:pointer;
        }

        .file-btn:hover{
            background:#dee2e6;
        }
        .file-input input[type=file]{
            position:absolute;
            inset:0;
            opacity:0;
            cursor:pointer;
        }
        .actions{
            margin-top:30px;
            display:flex;
            justify-content:flex-end;
        }
        .btn{
            background:#23446b;
            color:#fff;
            border:none;
            padding:10px 26px;
            border-radius:10px;
            font-weight:600;
            cursor:pointer;
            box-shadow:0 4px 10px rgba(0,0,0,.25);
        }
        .btn:hover{opacity:.9}
        .alert{
            padding:12px 18px;
            border-radius:8px;
            margin-bottom:20px;
            font-weight:500;
        }
        .success{background:#d4edda;color:#155724}
        .error{background:#f8d7da;color:#721c24}
    </style>
</head>
<body>
    <div class="header"></div>
    <div class="page-title">
        <a href="dashboard.php?kdsatker=<?= urlencode($kdsatker) ?>"><i class="fa-solid fa-arrow-left"></i></a>
        <h1>Import Data Tamu</h1>
    </div>

    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <h3>Upload Data Tamu</h3>
                <p>Pengisian dalam jumlah banyak dapat dilakukan sekaligus.</p>
            </div>

            <div class="card-body">
                <?php if(isset($_GET['status'])): ?>
                    <?php if($_GET['status'] === 'success'): ?>
                        <div class="alert success">✅ Data berhasil diimpor.</div>
                    <?php else: ?>
                        <div class="alert error">❌ Gagal mengimpor data.</div>
                    <?php endif; ?>

                    <script>
                        if (window.history.replaceState) {
                            const url = new URL(window.location);
                            url.searchParams.delete('status');
                            window.history.replaceState({}, document.title, url.pathname);
                        }
                    </script>
                <?php endif; ?>
                <form id="importForm" action="proses-import.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="kdsatker" value="<?= htmlspecialchars($kdsatker) ?>">
                    <label class="file-label">
                        <i class="fa-solid fa-file-excel excel-icon"></i>
                        Pilih File Excel
                    </label>
                    <div class="file-input">
                        <span class="file-placeholder">Tidak ada file yang dipilih</span>
                        <button type="button" class="file-btn">Pilih File</button>
                        <input type="file" name="file_excel" accept=".csv,.xlsx" required>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn" id="btnSubmit">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('importForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
        });
    </script>
</body>
</html>
