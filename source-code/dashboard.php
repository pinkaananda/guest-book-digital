<?php
    include "koneksi.php";
    date_default_timezone_set("Asia/Jakarta");

    $kdsatker = $_POST['kdsatker'] ?? $_GET['kdsatker'] ?? '694762';

    $filter_month   = $_GET['month'] ?? "";
    $today          = date("Y-m-d");
    $current_month  = date("Y-m");

    $is_filter_empty      = empty($filter_month);
    $is_filter_this_month = ($filter_month === $current_month);

    $jumlah_hari_ini = 0;

    if ($is_filter_empty || $is_filter_this_month) {
        $stmt = $conn->prepare("
            SELECT COUNT(*) 
            FROM tamu 
            WHERE tanggal = ? AND kdsatker = ?
        ");
        $stmt->bind_param("ss", $today, $kdsatker);
        $stmt->execute();
        $stmt->bind_result($jumlah_hari_ini);
        $stmt->fetch();
        $stmt->close();
    }

    $bulan_query = $is_filter_empty ? $current_month : $filter_month;

    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM tamu 
        WHERE DATE_FORMAT(tanggal,'%Y-%m') = ?
        AND kdsatker = ?
    ");
    $stmt->bind_param("ss", $bulan_query, $kdsatker);
    $stmt->execute();
    $stmt->bind_result($jumlah_bulan_ini);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM tamu 
        WHERE kdsatker = ?
    ");
    $stmt->bind_param("s", $kdsatker);
    $stmt->execute();
    $stmt->bind_result($jumlah_total);
    $stmt->fetch();
    $stmt->close();

    $page   = max(1, intval($_GET['page'] ?? 1));
    $limit  = (int)($_GET['limit'] ?? 10);
    $offset = ($page - 1) * $limit;

    $where       = "kdsatker = ?";
    $bind_types  = "s";
    $bind_vals   = [$kdsatker];

    if (!empty($filter_month)) {
        $where      .= " AND DATE_FORMAT(tanggal,'%Y-%m') = ?";
        $bind_types .= "s";
        $bind_vals[] = $filter_month;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM tamu WHERE $where");
    $stmt->bind_param($bind_types, ...$bind_vals);
    $stmt->execute();
    $stmt->bind_result($total_rows);
    $stmt->fetch();
    $stmt->close();

    $total_pages = max(1, ceil($total_rows / $limit));

    $sql = "
        SELECT * 
        FROM tamu 
        WHERE $where 
        ORDER BY tanggal DESC, created_at DESC 
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);

    $bind_types .= "ii";
    $bind_vals[] = $limit;
    $bind_vals[] = $offset;

    $stmt->bind_param($bind_types, ...$bind_vals);
    $stmt->execute();
    $res = $stmt->get_result();

    function qs(array $overrides = []) {
        return http_build_query(array_merge($_GET, $overrides));
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Daftar Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{
            box-sizing:border-box;
            min-width: 0;
        }
        html,body{
            margin:0;
            padding:0;
            width:100%;
            overflow-x:hidden;
        }
        html{
            min-height:100%;
            background:linear-gradient(180deg,#2a6099 0%, #0f7a6f 100%);
        }
        body{
            min-height:100vh;
            font-family:'Poppins',sans-serif;
            background:transparent;
            color:#0e2740;
        }
        .top-header{
            background: rgba(18,56,88,0.95);
            color: #fefefe;
            padding: 25px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }
        .container{
            max-width:1200px;
            width:100%;
            margin:auto;
            padding:20px;
        }
        .up{
            display:flex;
            flex-wrap:wrap;
            gap:16px;
            align-items:center;
            justify-content:space-between;
            padding: 10px 0;
        }
        .header-row{
            display:flex;
            align-items:center;
        }
        .judul{
            font-size:32px;
            font-weight: 600;
            color:#fff;
            text-shadow: 0 4px 12px #0e2740;
            margin:0;
        }
        .month-btn{
            background: #fff;
            color: #22466c;
            padding:10px 14px;
            border-radius:6px;
            font-size: 18px;
            font-weight:600;
            display:flex;
            gap:8px;
            align-items:center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            cursor:pointer;
        }
        #monthPicker{
            opacity:0;
            position:absolute;
            pointer-events:auto;
            width:0;
            height:0;
        }
        .date-icon {
            width: 18px;
            height: 18px;
            opacity: 0.7;
        }
        .stats{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
            margin:30px 0;
        }
        .card{
            background:#fff;
            padding:20px;
            border-radius:12px;
            box-shadow:0 8px 18px rgba(0,0,0,.12);
            display:flex;
            align-items:center;
            gap:16px;
        }
        .card .icon{
            font-size:42px;
            color:#23446b;

        }
        .card .text-wrap{
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .card h3{
            font-size:34px;
            margin:0;
            color:#23446b;
            font-weight:700;
        }
        .card p{
            margin:2px 0 0;
            color:#23446b;
            font-weight:600;
        }
        .card3{ 
            background: #D9B048; 
            color:#fff; 
        }
        .sub-header{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            justify-content:space-between;
            align-items:center;
            margin:24px 0 12px;
            color:#fff;
        }
        .sub-header .left{ 
            font-weight:600; 
            display:flex; 
            gap:10px; 
            align-items:center 
        }
        .sub-header .right{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }
        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            height:44px;
            padding:0 16px;
            border-radius:8px;
            font-size: 15px;
            font-weight:600;
            border:1px solid rgba(0,0,0,.05);
            background:#fff;
            color:#123a56;
            cursor:pointer;
            text-decoration:none;
        }
        .btn.blue{
            background: #123a56;
            color:#fff
        }
        .btn.disabled,
        .btn:disabled{
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
            font-size: 16px;
            font-weight: 200;
            border: none;
        }
        .export-wrapper{
            position:relative
        }
        .export-menu{
            display:none;
            position:absolute;
            right:0;
            top:45px;
            background:#fff;
            width:220px;
            border-radius:5px;
            box-shadow:0 10px 25px rgba(0,0,0,.25);
            overflow:hidden;
            z-index:999;
        }
        .export-row{
            padding:10px 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            cursor:pointer;
            position:relative;
            color: #000;
        }
        .export-row.active{
            background: #e8f1fb;
            font-weight:400;
        }
        .export-row:hover{
            background: #f3f4f6;
        }
        .export-row input{
            position:absolute;
            inset:0;
            opacity:0;
            cursor:pointer;
            z-index:2;
        }
        .export-footer{
            display:flex;
            justify-content:flex-end;
            gap:15px;
            padding:8px 20px;
            border-top:1px solid #D9D9D9;
        }

        .export-row:hover{
            background: #f3f4f6;
        }
        .export-row input{
            position:absolute;
            inset:0;
            opacity:0;
            cursor:pointer;
            z-index:2;
        }
        .export-footer{
            display:flex;
            justify-content:flex-end;
            align-items:center;
            gap:15px;
            padding:8px 20px;
            border-top:1px solid #D9D9D9;
            margin-top:6px;
        }
        .export-footer .btn{
            flex:0;
            padding:4px 25px;
            height:32px;
            font-size:16px;
            font-weight: 200;
            border: none;
            color:#2a6099;
        }
        .btn.cancel{
            color:#2a6099;
            font-size: 16px;
            font-weight: 200;
            border: none;
        }
        .table-wrapper{
            width:100%;
            overflow-x:auto;
            -webkit-overflow-scrolling: touch;

            border-radius:12px;
            background:#fff;
            box-shadow:0 8px 18px rgba(0,0,0,.12);
        }
        .table-wrapper table{
            min-width:900px;
        }
        .table-wrapper::-webkit-scrollbar{
            height:8px;
        }
        .table-wrapper::-webkit-scrollbar-thumb{
            background:rgba(0,0,0,0.25);
            border-radius:10px;
        }
        .table-wrapper::-webkit-scrollbar-track{
            background:transparent;
        }
        table{
            width:100%;
            border-collapse:collapse;
            border-radius:12px;
            overflow:hidden;
            background: #fff;
        }
        thead th{
            vertical-align: bottom;
            background: rgba(241,244,247,0.9);
            color: #23446b;
            padding:16px;
            text-align:left;
            font-weight:700;
            white-space: nowrap;
        }
        tbody td{
            padding:14px;
            border-bottom: 1px solid #f2f4f6;
            color: #000;
            font-size: 15px;
        }
        tbody tr:nth-child(even){ 
            background: rgba(0,0,0,0.02) 
        }
        .status{
            padding:6px 12px;
            border-radius:20px;
            font-weight:700;
            display:inline-block;
            min-width:70px;
            text-align:center;
            color:#fff;
        }
        .status.done{ 
            background: #5CBC74; 
        }
        .status.pending{ 
            background: #EC1C24; 
        }
        
        .detail-btn{
            background:#fff;
            border:1px solid #ddd;
            padding:6px 12px;
            border-radius:20px;
            text-decoration:none;
            color: #000;
        }
        .bottom{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-top:24px;
            color:#fff}
        .bottom select{
            padding:4px 8px;
            border-radius:6px;
            border:none}
        .pagination{
            display:flex;
            justify-content:center;
            align-items:center;
            color:#fff;
        }
        .numbers a{
            width: 40px;
            height: 40px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0;
            margin:0 4px;
            border-radius:50%;
            text-decoration:none;
            color:#fff;
            background: rgba(255,255,255,0.12);
        }
        .numbers a.active{ 
            background: #fff; 
            font-weight:700;
            color: #23446b; 
        }
        .prev{
            width: 40px;
            height: 40px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            margin:0 4px;
            border-radius:50%;
            text-decoration:none;
            color:#fff;
            background: rgba(255,255,255,0.12);
        }
        .next{
            width: 40px;
            height: 40px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            margin:0 4px;
            border-radius:50%;
            text-decoration:none;
            color: #fff;
            background: #23446b;
        }
        .pagination a.disabled{
            opacity: .4;
            pointer-events: none;
            cursor: default;
        }
        .wa-link{ 
            color: black; 
            text-decoration:none; 
            font-weight:400; 
        }
    </style>
</head>
<body>
    <div class="top-header"></div>
    <div class="container">
                <div class="up">
                    <div class="header-row">
                        <h2 class="judul">Dashboard Daftar Tamu</h2>
                    </div>
                    <div class="nav-right">
                        <label for="monthPicker" class="month-btn">
                            <span id="monthLabel">
                                <?php 
                                    if (!empty($filter_month)) {
                                        echo date("F Y", strtotime($filter_month.'-01'));
                                    } else {
                                        echo "Semua Data";
                                    }
                                ?>
                            </span>
                            <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                        </label>
                        <input id="monthPicker" type="month" value="<?php echo $filter_month; ?>" />
                    </div>
                </div>

                <div class="stats">
                    <div class="card card1">
                        <i class="fa-solid fa-calendar-day icon"></i>
                        <div class="text-wrap">
                            <h3><?= $jumlah_hari_ini ?></h3>
                            <p>Tamu Hari Ini</p>
                        </div>
                    </div>
                    <div class="card card2">
                        <i class="fa-solid fa-table-cells icon"></i>
                        <div class="text-wrap">
                            <h3><?= $jumlah_bulan_ini ?></h3>
                            <p>Tamu Bulan Ini</p>
                        </div>
                    </div>
                    <div class="card card3">
                        <i class="fa-solid fa-people-group icon"></i>
                        <div class="text-wrap">
                            <h3><?= $jumlah_total ?></h3>
                            <p>Tamu Keseluruhan</p>
                        </div>
                    </div>
                </div>

                <div class="sub-header">
                    <div class="left">
                        <i class="fa-solid fa-people-line"></i>
                        Data Kunjungan Tamu - Deputi Bidang Usaha Menengah
                    </div>
                    <div class="right">
                        <form action="import.php" method="post" enctype="multipart/form-data" style="display:inline-block;">
                            <input type="hidden" name="kdsatker" value="<?= htmlspecialchars($kdsatker) ?>">
                            <a href="import.php?kdsatker=<?= $kdsatker ?>" class="btn">
                                <i class="fa-solid fa-file-import"></i> Import Tamu
                            </a>
                        </form>
                        <div class="export-wrapper">
                            <button class="btn blue" id="btnExport">
                                <i class="fa-solid fa-download"></i> Export Data Tamu (Excel)
                                <i class="fa-solid fa-caret-down"></i>
                            </button>
                            <div id="exportMenu" class="export-menu">
                            <div class="export-row" id="rowTanggal">
                            <span class="label">Pilih Tanggal</span>
                            <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                            <input type="date" id="exportTanggal">
                        </div>
                        <div class="export-row" id="rowBulan">
                            <span class="label">Pilih Bulan</span>
                            <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                            <input type="month" id="exportBulan">
                        </div>
                        <div class="export-row export-all" id="rowSemua">
                            Semua
                        </div>

                        <div class="export-footer">
                            <button class="btn cancel" type="button" id="btnBatal">Batal</button>
                            <button class="btn disabled" type="button" id="btnUnduh" disabled>Unduh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Nama</th>
                    <th>Instansi Asal/Alamat</th>
                    <th>No. WhatsApp</th>
                    <th>Feedback</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = $offset + 1;
                while($row = $res->fetch_assoc()):
                    
                    $token = $row['feedback_token'] ?: '';
                    
                    $link_feedback = "https://{$_SERVER['HTTP_HOST']}/feedback.php?token=".$token;
                    $tanggal_kunjungan = date("d F Y", strtotime($row['tanggal']));

                    $pesan = "Halo, " . $row['nama'] . "!\n" .
                            "Kami ingin mengingatkan untuk mengisi feedback atas kunjungan Anda pada tanggal {$tanggal_kunjungan} melalui link di bawah ini ya.\n" .
                            "" . $link_feedback . "\n\n" .
                            "—\n" .
                            "Deputi Bidang Usaha Menengah\n" .
                            "Kementerian UMKM RI";

                    $message = rawurlencode($pesan);

                    $nohp_for_wa = preg_replace('/\D+/', '', $row['nohp']);
                    
                    if (strpos($nohp_for_wa, '62') !== 0) {
                        if (strpos($nohp_for_wa, '0') === 0) {
                            $nohp_for_wa = '62' . substr($nohp_for_wa,1);
                        }
                    }

                $wa_link = "https://wa.me/{$nohp_for_wa}?text={$message}";
            ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= date("d F Y", strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['instansi']) ?></td>
                    <td><a class="wa-link" href="<?= $wa_link ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($row['nohp']) ?></a></td>
                    <td>
                        <?php if ($row['feedback_status'] === 'done'): ?>
                            <span class="status done">Sudah</span>
                        <?php else: ?>
                            <span class="status pending">Belum</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="detail.php?id=<?= $row['id'] ?>&kdsatker=<?= $kdsatker ?>" class="detail-btn">Detail</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table></div>

        <div class="bottom">
            <form>
                Menampilkan
                <select name="limit" onchange="this.form.submit()">
                    <?php foreach([10,25,50,100,500,1000] as $l): ?>
                    <option value="<?= $l ?>" <?= $limit==$l?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach ?>
                </select>
                dari <?= $total_rows ?> entri
            </form>
            
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a class="prev" href="?<?= qs(['page'=>$page-1]) ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <a class="prev disabled">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <div class="numbers">
                    <?php
                    if ($page > 2) {
                        echo '<a href="?'.qs(['page'=>1]).'">1</a>';
                    }

                    if ($page > 3) {
                        echo '<span style="margin:0 6px">...</span>';
                    }

                    $start = max(1, $page - 1);
                    $end   = min($total_pages, $page + 1);

                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <a class="<?= $i==$page?'active':'' ?>"
                        href="?<?= qs(['page'=>$i]) ?>">
                        <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages - 2): ?>
                        <span style="margin:0 6px">...</span>
                    <?php endif; ?>

                    <?php if ($page < $total_pages - 1): ?>
                        <a href="?<?= qs(['page'=>$total_pages]) ?>">
                            <?= $total_pages ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if($page < $total_pages): ?>
                    <a class="next" href="?<?= qs(['page'=>$page+1]) ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <a class="next disabled">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        let exportType = null;
        let exportValue = null;

        const menu = document.getElementById('exportMenu');
        const btnExport = document.getElementById('btnExport');
        const btnUnduh = document.getElementById('btnUnduh');
        const btnBatal = document.getElementById('btnBatal');

        const rowTanggal = document.getElementById('rowTanggal');
        const rowBulan   = document.getElementById('rowBulan');
        const rowSemua   = document.getElementById('rowSemua');

        const tgl = document.getElementById('exportTanggal');
        const bln = document.getElementById('exportBulan');

        const labelTanggal = rowTanggal.querySelector('.label');
        const labelBulan   = rowBulan.querySelector('.label');

        function disableUnduh(){
            btnUnduh.disabled = true;
            btnUnduh.classList.add('disabled');
        }

        function enableUnduh(){
            btnUnduh.disabled = false;
            btnUnduh.classList.remove('disabled');
        }

        function resetExport(){
            exportType = null;
            exportValue = null;

            tgl.value = '';
            bln.value = '';

            labelTanggal.innerText = 'Pilih Tanggal';
            labelBulan.innerText   = 'Pilih Bulan';

            document.querySelectorAll('.export-row')
                .forEach(r => r.classList.remove('active'));

            disableUnduh();
        }
        
        btnExport.onclick = (e)=>{
            e.stopPropagation();
            menu.style.display = 'block';
        };

        rowTanggal.onclick = ()=>{
            resetExport();
            rowTanggal.classList.add('active');
            tgl.showPicker?.();
        };
        function formatTanggalIndonesia(dateStr){
            const bulan = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ];

            const d = new Date(dateStr);
            const hari = d.getDate();
            const namaBulan = bulan[d.getMonth()];
            const tahun = d.getFullYear();

            return hari + " " + namaBulan + " " + tahun;
        }

        function formatBulanIndonesia(monthStr){
            const bulan = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ];

            const [tahun, bln] = monthStr.split("-");
            return bulan[parseInt(bln,10)-1] + " " + tahun;
        }

        tgl.onchange = ()=>{
            if(!tgl.value){
                resetExport();
                return;
            }
            exportType  = 'tanggal';
            exportValue = tgl.value;
            labelTanggal.innerText = formatTanggalIndonesia(tgl.value);
            enableUnduh();
        };

        rowBulan.onclick = ()=>{
            resetExport();
            rowBulan.classList.add('active');
            bln.showPicker?.();
        };

        bln.onchange = ()=>{
            if(!bln.value){
                resetExport();
                return;
            }
            exportType  = 'bulan';
            exportValue = bln.value;
            labelBulan.innerText = formatBulanIndonesia(bln.value);
            enableUnduh();
        };

        rowSemua.onclick = ()=>{
            resetExport();
            rowSemua.classList.add('active');
            exportType = 'semua';
            enableUnduh();
        };

        btnBatal.onclick = ()=>{
            resetExport();
            menu.style.display = 'none';
        };

        btnUnduh.onclick = ()=>{
            if(!exportType) return;

            let url = "export-excel.php?tipe=" + exportType +
                    "&kdsatker=<?= $kdsatker ?>";

            if(exportType === 'tanggal'){
                url += "&tanggal=" + exportValue;
            }
            if(exportType === 'bulan'){
                url += "&bulan=" + exportValue;
            }

            window.location.href = url;
        };

        document.addEventListener('click',(e)=>{
            if(!menu.contains(e.target) && e.target !== btnExport){
                menu.style.display = 'none';
            }
        });

        resetExport();
        
        document.querySelector('.month-btn').addEventListener('click', function(){
        document.getElementById('monthPicker').showPicker?.();
        document.getElementById('monthPicker').focus();
        });

        document.getElementById('monthPicker').addEventListener('change', function(){
            const v = this.value;
            const url = new URL(window.location.href);
            
            if (v) {
                url.searchParams.set('month', v);
            } else {
                url.searchParams.delete('month');
            }

            url.searchParams.set('page', 1);
            window.location = url.toString();
        });
    </script>
</body>
</html>
