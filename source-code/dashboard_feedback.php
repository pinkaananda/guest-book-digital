<?php
    include "koneksi.php";

    $kdsatker = $_GET['kdsatker'] ?? '694762';
    $kdsatker = preg_replace('/[^0-9]/', '', $kdsatker);
    if ($kdsatker === '') $kdsatker = '694762';

    function esc($v){
        global $conn;
        return $conn->real_escape_string($v);
    }

    $rating   = $_GET['rating']  ?? 'all';
    $comment  = $_GET['comment'] ?? 'all';
    $dateMode = $_GET['date']    ?? 'all';
    $from     = $_GET['from']    ?? '';
    $to       = $_GET['to']      ?? '';

    $page   = max(1,(int)($_GET['page'] ?? 1));
    $limit  = (int)($_GET['limit'] ?? 6);
    $offset = ($page-1)*$limit;

    $where = "WHERE f.kdsatker = '".esc($kdsatker)."'";

    if($rating!='all'){
        $where .= " AND f.rating='".esc($rating)."'";
    }
    if($comment=='ada'){
        $where .= " AND f.komentar IS NOT NULL AND f.komentar<>''";
    }
    if($comment=='kosong'){
        $where .= " AND (f.komentar IS NULL OR f.komentar='')";
    }
    if ($dateMode === 'custom') {

        if ($from && !$to) {
            $where .= " AND DATE(f.created_at) = '" . esc($from) . "'";
        }
    
        elseif (!$from && $to) {
            $where .= " AND DATE(f.created_at) = '" . esc($to) . "'";
        }
    
        elseif ($from && $to) {
            $where .= " AND DATE(f.created_at) BETWEEN '" . esc($from) . "' AND '" . esc($to) . "'";
        }
    }
    
    $totalTamu = (int)$conn->query("
        SELECT COUNT(*) t FROM tamu WHERE kdsatker='".esc($kdsatker)."' 
    ")->fetch_assoc()['t'];

    $filled = (int)$conn->query("
        SELECT COUNT(*) t FROM tamu 
        WHERE kdsatker='".esc($kdsatker)."' AND feedback_status='done'
    ")->fetch_assoc()['t'];

    $empty = (int)$conn->query("
        SELECT COUNT(*) t FROM tamu 
        WHERE kdsatker='".esc($kdsatker)."' AND feedback_status='pending'
    ")->fetch_assoc()['t'];

    $stat = ['bad'=>0,'neutral'=>0,'good'=>0];
    $qStat = $conn->query("SELECT rating,COUNT(*) j FROM feedback f $where GROUP BY rating");
    while($s=$qStat->fetch_assoc()) $stat[$s['rating']]=$s['j'];

    $data = $conn->query("
        SELECT f.*
        FROM feedback f
        $where
        ORDER BY f.created_at DESC
        LIMIT $limit OFFSET $offset
    ");

    $totalData = (int)$conn->query("
        SELECT COUNT(*) t FROM feedback f $where
    ")->fetch_assoc()['t'];

    $totalPage = max(1,ceil($totalData/$limit));

    function q($k,$v){
        $u=$_GET;
        $u[$k]=$v;
        if(!isset($u['kdsatker'])) $u['kdsatker'] = '694762';
        return '?'.http_build_query($u);
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{
            box-sizing:border-box;
            font-family:Poppins}
        html{
            min-height:100%;
            background:linear-gradient(180deg, #2a6099 0%, #0f7a6f 100%);
        }
        body{
            min-height:100vh;
            margin:0;
            font-family:'Poppins',sans-serif;
            background:transparent;
            color:#1b1b1b;
        }
        .container{
            max-width:1440px;
            margin:auto;
            padding:30px
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
        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        h1{
            font-size:38px;
            color:#fff;
            margin:0
        }
        .card{
            background:#fff;
            border-radius:18px;
            padding:24px;
            box-shadow:0 12px 30px rgba(0,0,0,.25);
        }
        .card h3{
            font-size:20px;
            margin:0; 
            color: #23446b
        }
        .date-filter{
            position:relative;
            z-index:100
        }
        .date-btn{
            background:#fff;
            border:none;
            padding:14px 18px;
            border-radius:8px;
            font-weight:600;
            font-size: 16px;
            display:flex;
            gap:10px;
            cursor:pointer;
        }
        .date-icon {
            width: 18px;
            height: 18px;
            opacity: 0.7;
        }
        .dd-menu{
            display:none;
            position:absolute;
            top:56px;
            right: 0;
            width:280px;
            background:#fff;
            border-radius:16px;
            padding:14px;
            box-shadow:0 20px 40px rgba(0,0,0,.35);
        }
        .dd-menu.show{
            display:block;
        }
        .dropdown-filter{
            position:relative;
            z-index:100
        }
        .dropdown-filter button{
            background:transparent;
            border:1.5px solid rgba(255,255,255,.7);
            color:#fff;
            padding:8px 16px;
            border-radius:20px;
            font-weight:600;
            display:flex;
            gap:8px;
            align-items:center;
            cursor:pointer;
        }
        .dropdown-filter .dd-menu{
            width:200px;
        }
        .dropdown-filter .dd-menu.coment{
            width:250px;
        }
        .dd-menu a{
            display:block;
            padding:10px;
            border-radius:10px;
            text-decoration:none;
            color:#0e2740;
        }
        .dd-menu a:hover{
            background:#f1f1f1
        }
        .filter-row{display:flex;gap:12px;margin-top:28px}
        .filter-item{
            display:flex;
            align-items:center;
            padding:8px 10px;
            border-radius:10px;
            cursor:pointer;
            font-size:14px;
        }
        .filter-item:hover{
            background:#f2f4f7;
        }
        .filter-item.active{
            background:rgba(43,108,155,.12);
            font-weight:600;
        }
        .filter-item .check{
            width:18px;
            text-align:center;
            color:#2b6c9b;
            font-size:14px;
            margin-right:8px;
        }
        .filter-item .icon{
            width:20px;
            display:flex;
            justify-content:center;
        }
        .filter-item .text{
            flex:1;
        }
        .filter-btn-content{
            display:flex;
            align-items:center;
            gap:8px;
        }

        .filter-clear{
            margin-left:8px;
            font-size:14px;
            cursor:pointer;
            opacity:.8;
        }

        .filter-clear:hover{
            opacity:1;
        }
        .date-field{
            position:relative;
            margin-top:12px
        }
        .date-field label{
            position:absolute;
            top:-8px;
            left:12px;
            background:#fff;
            padding:0 6px;
            font-size:11px;
            font-weight:600;
            color:#777;
        }
        .date-field input{
            width:100%;
            padding:10px;
            border-radius:10px;
            border:1.5px solid #ddd;
        }
        .date-wrap{
            position:relative;
        }

        .date-wrap input[type="date"]{
            position:absolute;
            inset:0;
            opacity:0;
            pointer-events:none;
            border-color:#2b6c9b;
        }
        .fake-placeholder{
            border:1.5px solid #ddd;
            border-radius:10px;
            padding:10px 12px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:#aaa;
            font-size:14px;
            background:#fff;
            cursor:pointer;
        }

        .fake-placeholder span{
            font-weight:500;
        }
        .btn-row{
            display:flex;
            gap:12px;
            margin-top:14px;
            justify-content:flex-end;
        }
        .stats-row{
            display:grid;
            grid-template-columns:2fr 1fr;
            gap:24px;
            margin-top:30px;
        }
        .bar-row{
            display:flex;
            align-items:center;
            gap:12px;
            margin-top:14px}
        .bar{
            position:relative;
            flex:1;
            height:30px;
            background:#e6e6e6;
            border-radius:20px;
            overflow:hidden;
        }
        .bar-fill{
            position:absolute;
            left:0;
            top:0;
            height:100%;
            border-radius:20px;
        }
        .bar-number{
            position:absolute;
            right:-22px;
            top:50%;
            transform:translateY(-50%);
            font-size:13px;
            font-weight:600;
            color:#555;
            white-space:nowrap;
        }
        .bar span{
            height:100%;
            display:flex;
            align-items:center;
            justify-content:flex-end;
            padding-right:10px;
            font-size:13px;
            font-weight:700;
            color:#fff;
            min-width:36px;
        }
        .bar-wrap{
            flex:1;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .bar-value{
            min-width:28px;
            font-weight:600;
            font-size:13px;
            color:#555;
        }
        .red{
            background:#e53935
        }
        .yellow{
            background:#f9a825
        }
        .green{
            background:#43a047
        }
        .total-main{
            display:flex;
            justify-content:space-between;
            align-items:center
        }
        .total-main h3{
            font-size:40px;
            margin:0; 
            color: #23446b
        }
        .total-main p{
            margin:4px 0 0;
            font-weight:600;
            color:#22466C
        }
        .total-main i{
            font-size:46px;
            color:#1f5d8c}
        .mini-stats{
            display:flex;
            gap:14px;
            margin-top:14px
        }
        .mini{
            flex:1;
            padding:14px;
            border-radius:12px;
            font-weight:700
        }
        .mini.green{
            background:#43a047;
            color:#fff; 
            font-weight: 600;
        }
        .mini.gray{
            background:#e3e3e3; 
            font-weight: 600;
        }
        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
            gap:20px;
            margin-top:28px;
        }
        .item{
            background:#fff;
            border-radius:18px;
            padding:10px
        }
        .inner-box{
            border:1.5px solid #e6e6e6;
            border-radius:14px;
            padding:14px
        }
        .item-header{
            background:#2b5f9a;
            color:#fff;
            padding:8px 12px;
            border-radius:10px;
            font-weight:600;
            display:flex;
            gap:8px;
            align-items:center;
        }
        .item-body{
            margin:14px 10px;
            font-size:16px;
            height:80px
        }
        .item-footer{
            display:flex;
            justify-content:space-between;
            align-items:center;
            font-size:12px;
            color:#777
        }
        .emoji{
            background:#fff;
            width:40px;
            height:40px;
            border-radius:6px;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 4px 10px rgba(0,0,0,.25)
        }
        .bottom{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-top:30px;
            color:#fff;
        }
        .pagination{
            margin-top:22px;
            display:flex;
            gap: 4px;
            justify-content:center;
            align-items:center;
            color:#fff;
        }
        .pagination a,
        .pagination .numbers a{
            width:40px;
            height:40px;
            padding:0;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            text-decoration:none;
            font-weight:600;
            background:rgba(255,255,255,0.12);
            color:#fff;
        }
        .pagination .numbers{
            display:flex;
            gap:4px;
        }
        .pagination .numbers a.active{
            background:#fff;
            color:#23446b;
            font-weight:700;
        }
        .pagination a.prev,
        .pagination a.next{
            background:rgba(255,255,255,0.12);
        }
        .pagination a.next{
            background:#23446b;
        }
        .pagination a.disabled{
            opacity:.4;
            pointer-events:none;
        }
        .pagination .ellipsis{
            width:40px;
            height:40px;
            display:inline-flex;
            align-items:flex-end;
            justify-content:center;
            padding-bottom:6px;
            font-weight:700;
            color:#fff;
            opacity:.7;
        }       
        .btn-apply {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: 0.2s;
            background: #fff;
        }
        .btn-apply.active {
            color: #2a6099;
            cursor: pointer;
        }
        .btn-cancel{
            background:transparent;
            border:none;
            padding:10px 16px;
            border-radius:8px;
            font-weight:500;
            color: #2A6099;
            cursor:pointer;
        }
        .btn-cancel:hover{
            color:#9ca3af;
        }
</style>
</head>
<body>
    <div class="top-header"></div>
    <div class="container">
        <div class="topbar">
            <h1>Feedback</h1>
            <div class="date-filter">
                <button class="date-btn" onclick="toggleDD('ddDate')">
                    <?php
                        if($dateMode=='custom'){
                            if($from && $to){
                                echo date('d M Y',strtotime($from)).' – '.date('d M Y',strtotime($to));
                            }elseif($from){
                                echo date('d M Y',strtotime($from));
                            }elseif($to){
                                echo date('d M Y',strtotime($to));
                            }else{
                                echo 'Pilih Tanggal';
                            }
                        }else{
                            echo 'Semua Data';
                        }
                    ?>
                    <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                </button>

                <div class="dd-menu" id="ddDate">
                <div class="filter-item <?= $dateMode=='all'?'active':'' ?>"
                    onclick="setDateAll()">
                    <div class="check"><?= $dateMode=='all'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="text">Semua Data</div>
                </div>
                <div class="filter-item <?= $dateMode=='custom'?'active':'' ?>"
                    onclick="selectDateMode('custom')">
                    <div class="check"><?= $dateMode=='custom'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="text">Rentang Tanggal Custom <i class="fa-solid fa-chevron-right"></i> </div>
                </div>

                <form id="dateForm" method="get">

                    <input type="hidden" name="rating" value="<?= $rating ?>">
                    <input type="hidden" name="comment" value="<?= $comment ?>">
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="date" id="dateModeInput" value="<?= $dateMode ?>">

                    <div class="date-field date-wrap">
                        <label>Setelah</label>
                        <input type="date" name="from" id="fromDate" value="<?= $from ?>" data-target="fromText">
                        <div class="fake-placeholder" onclick="document.getElementById('fromDate').showPicker()">
                            <span id="fromText"><?= $from ? date('d/m/Y', strtotime($from)) : 'dd/mm/yyyy' ?></span>
                            <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                        </div>
                    </div>
                    <div class="date-field date-wrap">
                        <label>Sebelum</label>
                        <input type="date" name="to" id="toDate" value="<?= $to ?>" data-target="toText">
                        <div class="fake-placeholder" onclick="document.getElementById('toDate').showPicker()">
                            <span id="toText"><?= $to ? date('d/m/Y', strtotime($to)) : 'dd/mm/yyyy' ?></span>
                            <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon">
                        </div>
                    </div>
                    <div class="btn-row">
                        <button type="button" class="btn-cancel" onclick="resetDate()">Batal</button>
                        <button type="submit" id="applyDateBtn" class="btn-apply" disabled>Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="stats-row">
        <div class="card">
            <h3><?= $totalData ?> Tanggapan Keseluruhan</h3>
            <div class="bar-row">
                <img src="assets/dislike.png" width="22">
                <div class="bar">
                    <?php if($stat['bad'] > 0): 
                        $pct = ($stat['bad'] / max(1,$totalData)) * 100;
                    ?>
                    <div class="bar-fill red" style="width:<?= $pct ?>%">
                        <span class="bar-number"style="color:#e53935;"><?= $stat['bad'] ?></span>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="bar-row">
                <img src="assets/smiley.png" width="22">
                <div class="bar">
                    <?php if($stat['neutral'] > 0): 
                        $pct = ($stat['neutral'] / max(1,$totalData)) * 100;
                    ?>
                    <div class="bar-fill yellow" style="width:<?= $pct ?>%">
                        <span class="bar-number"style="color: #f9a825;"><?= $stat['neutral'] ?></span>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="bar-row">
                <img src="assets/like.png" width="22">
                <div class="bar">
                    <?php if($stat['good'] > 0): 
                        $pct = ($stat['good'] / max(1,$totalData)) * 100;
                    ?>
                    <div class="bar-fill green" style="width:<?= $pct ?>%">
                        <span class="bar-number" style="color: #43a047"><?= $stat['good'] ?></span>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="total-main">
                <div>
                    <p>Total Tamu</p>
                    <h3><?= $totalTamu ?></h3>
                </div>
                <i class="fa-solid fa-people-group"></i>
            </div>
            <div class="mini-stats">
                <div class="mini green">Sudah Mengisi<br><?= $filled ?></div>
                <div class="mini gray">Belum Mengisi<br><?= $empty ?></div>
            </div>
        </div>
    </div>
    <div class="filter-row">
        <div class="dropdown-filter">
            <button type="button" onclick="toggleDD('ddRating')" id="ratingBtn">
                Semua Tanggapan
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div class="dd-menu" id="ddRating">
                <div class="filter-item <?= $rating=='good'?'active':'' ?>"
                    onclick="toggleFilter('rating','good')">
                    <div class="check"><?= $rating=='good'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="text">Tanggapan</div>
                    <div class="icon"><img src="assets/like.png" width="16"></div>
                </div>
                <div class="filter-item <?= $rating=='neutral'?'active':'' ?>"
                    onclick="toggleFilter('rating','neutral')">
                    <div class="check"><?= $rating=='neutral'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="text">Tanggapan</div>
                    <div class="icon"><img src="assets/smiley.png" width="16"></div>
                </div>
                <div class="filter-item <?= $rating=='bad'?'active':'' ?>"
                    onclick="toggleFilter('rating','bad')">
                    <div class="check"><?= $rating=='bad'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="text">Tanggapan</div>
                    <div class="icon"><img src="assets/dislike.png" width="16"></div>
                </div>
            </div>
        </div>
        <div class="dropdown-filter">
            <button type="button" onclick="toggleDD('ddComment')" id="commentBtn">
                Semua Komentar
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div class="dd-menu coment" id="ddComment">
                <div class="filter-item <?= $comment=='ada'?'active':'' ?>"
                    onclick="toggleFilter('comment','ada')">
                    <div class="check"><?= $comment=='ada'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="icon"></div>
                    <div class="text">Ada Komentar</div>
                </div>
                <div class="filter-item <?= $comment=='kosong'?'active':'' ?>"
                    onclick="toggleFilter('comment','kosong')">
                    <div class="check"><?= $comment=='kosong'?'<i class="fa-solid fa-check"></i>':'' ?></div>
                    <div class="icon"></div>
                    <div class="text">Tidak Ada Komentar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid">
        <?php while($r=$data->fetch_assoc()): ?>
        <div class="item">
            <div class="inner-box">
                <div class="item-header">
                    <i class="fa-solid fa-circle-user"></i>
                    <?= $r['anonim']?'Anonim':htmlspecialchars($r['nama']) ?>
                </div>
                <div class="item-body"><?= $r['komentar']?:'-' ?></div>
                    <div class="item-footer">
                        <span><?= date('d M Y',strtotime($r['created_at'])) ?></span>
                    <div class="emoji">
                        <?= $r['rating']=='good'?'<img src="assets/like.png", width="25px">':($r['rating']=='neutral'?'<img src="assets/smiley.png", width="25px">':'<img src="assets/dislike.png", width="25px">') ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile ?>
    </div>
    <div class="bottom">
        <form>
            Menampilkan
            <select name="limit" onchange="this.form.submit()">
                <?php foreach([6,18,30] as $l): ?>
                <option value="<?= $l ?>" <?= $limit==$l?'selected':'' ?>><?= $l ?></option>
                <?php endforeach ?>
            </select>
            dari <?= $totalData ?> entri
        </form>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a class="prev" href="<?= q('page',$page-1) ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php else: ?>
                <a class="prev disabled">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <div class="numbers">
                <?php
                    if($page > 2){
                        echo '<a href="'.q('page',1).'">1</a>';
                    }
                    if($page > 3){
                        echo '<span class="ellipsis">...</span>';
                    }
                    $start = max(1,$page-1);
                    $end   = min($totalPage,$page+1);
                    for($i=$start;$i<=$end;$i++){
                        echo '<a class="'.($i==$page?'active':'').'" href="'.q('page',$i).'">'.$i.'</a>';
                    }
                    if($page < $totalPage-2){
                        echo '<span class="ellipsis">...</span>';

                    }
                    if($page < $totalPage-1){
                        echo '<a href="'.q('page',$totalPage).'">'.$totalPage.'</a>';
                    }
                ?>
            </div>
            <?php if($page < $totalPage): ?>
                <a class="next" href="<?= q('page',$page+1) ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <a class="next disabled">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <script>
        let selectedDateMode = "<?= $dateMode ?>";
        function selectDateMode(mode){
            selectedDateMode = mode;
            document.getElementById('dateModeInput').value = mode;

            document.querySelectorAll('#ddDate .filter-item').forEach(i=>{
                i.classList.remove('active');
                i.querySelector('.check').innerHTML = '';
            });
            const active = [...document.querySelectorAll('#ddDate .filter-item')]
                .find(i => i.innerText.includes(mode=='all'?'Semua':'Rentang'));
            if(active){
                active.classList.add('active');
                active.querySelector('.check').innerHTML = '<i class="fa-solid fa-check"></i>';
            }
        }
        function resetDate(){
            const url = new URL(window.location.href);
            url.searchParams.delete('date');
            url.searchParams.delete('from');
            url.searchParams.delete('to');
            url.searchParams.set('page',1);
            window.location.href = url.toString();
        }
        function toggleFilter(key,value){
            const url = new URL(window.location.href);
            const current = url.searchParams.get(key) || 'all';
            if(current === value){
                url.searchParams.set(key,'all');
            }else{
                url.searchParams.set(key,value);
            }
            url.searchParams.set('page',1);
            window.location.href = url.toString();
        }
        function toggleDD(id){
            const el = document.getElementById(id);
            document.querySelectorAll('.dd-menu').forEach(d=>{
                if(d!==el) d.classList.remove('show');
            });
            el.classList.toggle('show');
        }
        document.addEventListener('mousedown',function(e){
            if(!e.target.closest('.dd-menu') &&
            !e.target.closest('.dropdown-filter') &&
            !e.target.closest('.date-filter')){
                document.querySelectorAll('.dd-menu').forEach(d=>d.classList.remove('show'));
            }
        });
        function setDateAll(){
            const url = new URL(window.location.href);
            url.searchParams.set('date','all');
            url.searchParams.delete('from');
            url.searchParams.delete('to');
            url.searchParams.set('page',1);
            window.location.href = url.toString();
        }
        document.querySelectorAll('input[name="from"], input[name="to"]').forEach(input=>{
            input.addEventListener('change', ()=>{
                selectedDateMode = 'custom';
                document.getElementById('dateModeInput').value = 'custom';
                document.querySelectorAll('#ddDate .filter-item').forEach(i=>{
                    i.classList.remove('active');
                    i.querySelector('.check').innerHTML = '';
                });
                const customItem = [...document.querySelectorAll('#ddDate .filter-item')]
                    .find(i => i.innerText.includes('Rentang'));
                if(customItem){
                    customItem.classList.add('active');
                    customItem.querySelector('.check').innerHTML =
                        '<i class="fa-solid fa-check"></i>';
                }
            });
        });
        function formatDMY(value){
            if(!value) return 'dd/mm/yyyy';
            const [y,m,d] = value.split('-');
            return `${d}/${m}/${y}`;
        }

        document.querySelectorAll('input[type="date"]').forEach(input=>{
            input.addEventListener('change', function(){
                const targetId = this.dataset.target;
                const span = document.getElementById(targetId);
                if(span){
                    span.textContent = formatDMY(this.value);
                    span.style.color = '#0e2740';
                }
            });
        });

        const fromDate = document.getElementById('fromDate');
        const toDate   = document.getElementById('toDate');
        const applyBtn = document.getElementById('applyDateBtn');

        function checkDateFilled() {
            if (fromDate.value || toDate.value) {
                applyBtn.classList.add('active');
                applyBtn.disabled = false;
            } else {
                applyBtn.classList.remove('active');
                applyBtn.disabled = true;
            }
        }
        fromDate.addEventListener('change', () => {
            document.getElementById('fromText').innerText =
                formatDate(fromDate.value);
            checkDateFilled();
        });

        toDate.addEventListener('change', () => {
            document.getElementById('toText').innerText =
                formatDate(toDate.value);
            checkDateFilled();
        });

        function formatDate(val) {
            if (!val) return 'dd/mm/yyyy';
            const d = new Date(val);
            return d.toLocaleDateString('id-ID');
        }

        checkDateFilled();

        function updateFilterLabels(){
            const params = new URLSearchParams(window.location.search);

            const rating = params.get('rating') || 'all';
            const ratingBtn = document.getElementById('ratingBtn');

            if(ratingBtn){
                let text = 'Semua Tanggapan';

                if(rating === 'good'){
                    text = '<img src="assets/dislike.png" width="16"> Tanggapan Baik';
                }else if(rating === 'neutral'){
                    text = '<img src="assets/smiley.png" width="16"> Tanggapan Netral';
                }else if(rating === 'bad'){
                    text = '<img src="assets/like.png" width="16"> Tanggapan Buruk';
                }
                ratingBtn.innerHTML = `${text} <i class="fa-solid fa-chevron-down"></i>`;
            }

            const comment = params.get('comment') || 'all';
            const commentBtn = document.getElementById('commentBtn');

            if(commentBtn){
                let text = 'Semua Komentar';

                if(comment === 'ada'){
                    text = 'Ada Komentar';
                }else if(comment === 'kosong'){
                    text = 'Tidak Ada Komentar';
                }

                commentBtn.innerHTML = `${text} <i class="fa-solid fa-chevron-down"></i>`;
            }
        }

        function updateFilterLabels(){
        const params = new URLSearchParams(window.location.search);

        const rating = params.get('rating') || 'all';
        const ratingBtn = document.getElementById('ratingBtn');

        if(ratingBtn){
            if(rating === 'all'){
                ratingBtn.innerHTML = `
                    <div class="filter-btn-content">
                        Semua Tanggapan
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                `;
            }else{
                let text = '';
                let icon = '';

                if(rating === 'good'){
                    text = 'Tanggapan';
                    icon = 'assets/like.png';
                }else if(rating === 'neutral'){
                    text = 'Tanggapan';
                    icon = 'assets/smiley.png';
                }else if(rating === 'bad'){
                    text = 'Tanggapan';
                    icon = 'assets/dislike.png';
                }

                ratingBtn.innerHTML = `
                    <div class="filter-btn-content">
                        ${text}
                        <img src="${icon}" width="16">
                        <span class="filter-clear" onclick="clearFilter(event,'rating')">✕</span>
                    </div>
                `;
            }
        }

        const comment = params.get('comment') || 'all';
        const commentBtn = document.getElementById('commentBtn');

        if(commentBtn){
            if(comment === 'all'){
                commentBtn.innerHTML = `
                    <div class="filter-btn-content">
                        Semua Komentar
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                `;
            }else{
                let text = comment === 'ada'
                    ? 'Ada Komentar'
                    : 'Tidak Ada Komentar';

                commentBtn.innerHTML = `
                    <div class="filter-btn-content">
                        ${text}
                        <span class="filter-clear" onclick="clearFilter(event,'comment')">✕</span>
                    </div>
                `;
            }
        }}
        function clearFilter(e,key){
            e.stopPropagation(); 
            const url = new URL(window.location.href);
            url.searchParams.set(key,'all');
            url.searchParams.set('page',1);
            window.location.href = url.toString();
        }
        updateFilterLabels();
    </script>
</body>
</html>
