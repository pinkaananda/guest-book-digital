<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Feedback Pengunjung — UMKM</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *{
      box-sizing:border-box
    }
    html{
      min-height:100%;
      background:linear-gradient(180deg,#254766 0%, #0f706f 100%);
    }
    body{
      min-height:100vh;
      margin:0;
      font-family:'Poppins',sans-serif;
      background:transparent;
      color:#1b1b1b;
      background:linear-gradient(180deg,#254766 0%, #0f706f 100%);
      overflow-x:hidden;
    }
    header{
      max-width:1440px;
      margin:0 auto;
      padding:24px 100px;
      color:#fff;
    }
    header h1{
      margin:0;
      font-size:42px;
      font-weight:700;
    }
    header p{
      margin-top:0;
      font-size:16px;
      font-weight:400;
    }
    main.wrap{
      max-width:1440px;
      height: 100%;
      margin:0 auto;
      background:#fff;
      border-radius:40px 40px 0 0;
      box-shadow:0 18px 40px rgba(4,23,43,.18);
      padding:40px 45px 20px 120px;
      position:relative;
      overflow:hidden;
    }
    .pattern{
      position:absolute;
      width:90px;
      pointer-events:none;
    }
    .pattern.left{
      left:-20px; 
      top:270px;
    }
    .pattern.right{
      right:-10px; 
      bottom:280px; 
      transform:rotate(180deg);
    }
    .field{
      margin:0 20px 10px
    }
    .field label{
      font-size:16px;
      font-weight:600;
      color:#22466C;
    }
    .field p{
      margin:6px 0 12px;
      font-size:14px;
      color:#22466C;
    }
    .emoji-wrap{
      width:65%;
      display:flex;
      gap:25px;
    }
    .emoji-wrap input{
      display:none;
    }
    .emoji{
      padding:14px 14px 10px;
      border-radius:16px;
      transition:.2s;
      cursor:pointer;
    }
    .emoji img{
      width:50px;
      margin-right:6px
    }
    .emoji:hover{
      background:#f2f2f2;
      transform:scale(1.1);
    }
    input:checked + .emoji{
      background:#214b6a;
      transform:scale(1.15);
    }
    textarea{
      width:90%;
      height:110px;
      background:#f3f4f6;
      border-radius:12px;
      padding:14px;
      border:1px solid rgba(0,0,0,.08);
      font-size:15px;
      resize:none;
      outline:none;
      font-family:"Poppins",sans-serif;
    }
    .anon-wrap{
      margin:0 20px;
      font-size:14px;
      color:#22466C;
    }
    .actions{
      display:flex;
      justify-content:flex-end;
      padding: 0 100px;
    }
    .btn-save{
      background:#2A6099;
      color:#fff;
      border:0;
      padding:13px 26px;
      border-radius:18px;
      font-weight:600;
      cursor:pointer;
      box-shadow:0 14px 30px rgba(33,69,106,.16);
    }
    .popup-bg{
      position:fixed; inset:0;
      background:rgba(0,0,0,.45);
      display:none;
      align-items:center;
      justify-content:center;
    }
    .popup{
      background:#fff;
      padding:28px;
      border-radius:16px;
      width:320px;
      text-align:center;
    }
    .popup button{
      margin-top:10px;
      border-radius:8px;
      padding: 10px;
      border:0;
      background:#214b6a;
      color:white;
      cursor:pointer;
    }

    @media(max-width:768px){
      header{
        padding:34px 40px;
      }
      header h1{ 
        font-size:36px; 
      }
      header p{ 
        font-size:14px; 
        line-height:1.5; 
      }
      main.wrap{
        padding:28px 40px 28px 50px;
        border-radius:32px 32px 0 0;
      }
      .pattern{
      width:80px;
      }
      .field label{ 
        font-size:14px; 
      }
      .field p{ 
        font-size:12px; 
      }
      .emoji-wrap{
        width:80%; 
        gap:20px;
      }
      .emoji img{
        width:48px;
      }
      textarea{
        width:100%; 
        height:100px; 
        font-size:12px;
      }
      .anon-wrap{
        font-size: 13px;
      }
      .actions{
        padding:10px 10px; 
        justify-content:flex-end;
      }
      .btn-save{
        padding:12px 24px; 
        font-size:12px;
      }
    }

    @media(max-width:480px){
      header{
        padding:38px 38px;
      }
      header h1{ 
        font-size:30px; 
      }
      header p{ 
        font-size:12.5px; 
      }
      main.wrap{
        padding:20px 18px 36px;
        border-radius:28px 28px 0 0;
      }
      .pattern{
        width:60px;
      }
      .field label{ 
        font-size:14px; 
      }
      .field p{ 
        font-size:12px; 
      }
      .emoji-wrap{
        width:80%; 
        gap:12px;
      }
      .emoji img{
        width:38px;
      }
      textarea{
        height:90px; 
        font-size:13px;
      }
      .anon-wrap{
      font-size:12px;
      }
      .actions{
        padding:15px 10px; 
        justify-content:flex-end;
      }
      .btn-save{
        padding:10px 16px; 
        font-size:12px;
      }
    }

    @media(max-width:380px){
      header{
        padding:50px 25px;
      }
      header h1{ 
        font-size:30px; 
      }
      header p{ 
        font-size:11px; 
      }
      main.wrap{
        padding:30px 12px 30px;
        border-radius:24px 24px 0 0;
      }
      .field label{ 
        font-size:13px; 
      }
      .field p{ 
        font-size:11px; 
      }
      .emoji-wrap{
        gap:10px;
      }
      .emoji img{
        width:35px;
      }
      textarea{
        height:80px; 
        font-size:12px;
      }
      .btn-save{
        padding:8px 20px; 
        font-size:11px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Halo!</h1>
    <p>Kami ingin mengetahui bagaimana pengalaman Anda selama melakukan kunjungan hari ini.</p>
  </header>

  <main class="wrap">
    <img src="assets/pola.png" class="pattern left">
    <img src="assets/pola.png" class="pattern right">

    <form id="fbForm" method="POST" action="/bukut/feedback_simpan.php">
      <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

      <div class="field">
        <label>Bagaimana kunjungan Anda?</label>
        <p>Pilih emoji yang menggambarkan pengalaman Anda</p>

        <div class="emoji-wrap">
          <label>
            <input type="radio" name="rating" value="bad" required>
            <div class="emoji"><img src="assets/dislike.png"></div>
          </label>
          <label>
            <input type="radio" name="rating" value="neutral">
            <div class="emoji"><img src="assets/smiley.png"></div>
          </label>
          <label>
            <input type="radio" name="rating" value="good">
            <div class="emoji"><img src="assets/like.png"></div>
          </label>
        </div>
      </div>

      <div class="field">
        <label>Feedback Tambahan (opsional)</label>
        <textarea name="komentar" maxlength="100" placeholder="Ceritakan pengalaman Anda atau berikan saran"></textarea>
      </div>

      <div class="anon-wrap">
        <label>
          <input type="checkbox" name="anonim" value="1">
          Kirim tanpa nama (anonim)
        </label>
      </div>

      <div class="actions">
        <button type="submit" class="btn-save">Kirim Feedback</button>
      </div>
    </form>
  </main>

  <div id="popup" class="popup-bg">
    <div class="popup">
      <img src="https://cdn-icons-png.flaticon.com/128/14090/14090371.png" style="width:72px">
      <h3 style="margin-top:12px;">Berhasil!</h3>
      <p style="color:#596a76">Feedback Anda terkirim.</p>
      <button onclick="closePopup()">OK</button>
    </div>
  </div>

  <script>
    const form = document.getElementById("fbForm");
    const btnSave = document.querySelector(".btn-save");
    let isSending = false;

    form.addEventListener("submit", async function(e){
        e.preventDefault();
        if (isSending) return;
        isSending = true;

        btnSave.disabled = true;
        btnSave.innerText = "Mengirim...";

        const fd = new FormData(form);

        try {
            const res = await fetch("/bukut/feedback_simpan.php", {
                method: "POST",
                body: fd
            });

            const text = await res.text();

            if (text.trim() === "OK") {
        showPopup();
        const token = form.token.value;
        form.reset();
        form.token.value = token;
    }

    else {
                alert(text);
            }
        } catch(err){
            alert("Gagal mengirim: " + err);
        }

        isSending = false;
        btnSave.disabled = false;
        btnSave.innerText = "Kirim Feedback";
    });

    function showPopup(){
        document.getElementById("popup").style.display='flex';
    }
    function closePopup(){
        document.getElementById("popup").style.display='none';
    }
  </script>

</body>
</html>
