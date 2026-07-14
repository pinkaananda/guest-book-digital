<?php
  $kdsatker = $_GET['kdsatker'] ?? '694762';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Buku Tamu — UMKM</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *{
      box-sizing:border-box;
      margin:0;
      padding:0;
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
    }
    body{
      font-family:"Poppins",sans-serif;
      background:linear-gradient(180deg,#254766 0%, #0f706f 100%);
      color:#132233;
      -webkit-font-smoothing:antialiased;
      overflow-x:hidden;
    }
    .header{
      max-width:1440px;
      margin:0 auto;
      padding:clamp(28px,5vw,50px) clamp(20px,5vw,58px);
      display:flex;
      gap:clamp(14px,10vw,90px);
      align-items:flex-start;
      color:#fff;
    }
    .brand img{
      width:clamp(200px,18vw,160px);
      filter:brightness(0) invert(1);
      padding: 16px 0;
    }
    .hero h1{
      font-size:clamp(22px,4vw,36px);
      font-weight:700;
      line-height:1.05;
      text-shadow:0 2px 6px rgba(0,0,0,.12);
    }
    .hero p{
      margin-top:6px;
      font-size:clamp(12px,2vw,14px);
      color:#dbe9f2;
      font-weight:500;
    }
    .wrap{
      max-width:1440px;
      margin:0 auto;
      padding:clamp(24px,5vw,56px);
      padding-bottom:clamp(24px,4vw,40px);
      background:#fff;
      border-radius:40px 40px 0 0;
      box-shadow:0 18px 40px rgba(4,23,43,.18);
      position:relative;
      overflow:hidden;
    }
    .pattern{
      position:absolute;
      width:clamp(70px,8vw,100px);
      pointer-events:none;
      opacity:.9;
    }
    .pattern.left{
      left:-20px;
      top:420px;
    }
    .pattern.right{
      right:-20px;
      bottom:420px;
    }
    .intro{
      font-size:clamp(14px,2vw,16px);
      font-weight:600;
      color:#1f3b63;
      margin-bottom:20px;
    }
    .form-grid{
      display:grid;
      grid-template-columns:repeat(2,minmax(0,1fr));
      gap:clamp(16px,4vw,40px);
      align-items:stretch;
      margin: 20px;
    }
    .form-grid > div{
      display:flex;
      flex-direction:column;
      gap:18px;
    }
    .field label{
      display:block;
      margin-bottom:6px;
      font-size:clamp(13px,2vw,16px);
      font-weight:600;
      color:#17243a;
    }
    .muted{
      font-size:clamp(10px,1.6vw,11px);
      color:#9ea8b3;
    }
    .control{
      display:flex;
      align-items:center;
      gap:8px;
      padding:12px 10px;
      background:#f3f4f6;
      border-radius:12px;
      border:1px solid rgba(18,34,50,.06);
      box-shadow:0 6px 12px rgba(18,34,50,.06);
      min-height:48px;
      transition:.2s ease;
    }
    .control:focus-within{
      border-color:#214b6a;
      box-shadow:0 0 0 3px rgba(33,75,106,.12);
    }
    .control input{
      border:0;
      outline:none;
      background:transparent;
      font-size:clamp(13px,2vw,14px);
      flex:1;
      color:#203245;
    }
    ::placeholder{
      color:#999;
      opacity:.5;
    }
    .date-wrap{
      position:relative;
    }
    .date-display{
      width:100%;
      cursor:pointer;
      color:#9aa7b2;
    }
    .date-icon{
      width:18px;
      height:18px;
      opacity:.7;
      cursor:pointer;
    }
    .name-wrapper{
      display:flex;
      flex-direction:column;
      gap:6px;
    }
    .btn-add{
      align-self:flex-end;
      background:#D9B048;
      color:#fff;
      border:0;
      padding:10px 14px;
      border-radius:12px;
      font-size:clamp(11px,1.6vw,13px);
      font-weight:600;
      cursor:pointer;
      box-shadow:0 6px 10px rgba(33,69,106,.12);
    }
    .name-extra{
      display:flex;
      gap:8px;
      align-items:center;
    }
    .name-extra input{
      flex:1;
      padding:12px;
      border-radius:10px;
      border:1px solid #e6e7ea;
      background:#f3f4f6;
      outline:none;
    }
    .small-circle{
      width:36px;
      height:36px;
      border-radius:10px;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#f1f1f3;
      border:1px solid #e6e7ea;
      cursor:pointer;
    }
    .photo-box{
      min-height:140px;
      border-radius:14px;
      padding:20px;
      background:#f3f4f6;
      border:1px solid rgba(18,34,50,.04);
      box-shadow:0 8px 16px rgba(5,23,38,.05);
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:10px;
      cursor:pointer;
    }
    .photo-box img.icon{
      width:36px;
      opacity:.6;
    }
    #previewWrap{
      position:relative;
      margin-top:10px;
    }
    .preview-img{
      width:180px;
      border-radius:12px;
      box-shadow:0 4px 14px rgba(0,0,0,.15);
    }
    .delete-photo{
      position:absolute;
      top:50%;
      right:50%;
      transform: translate(-50%, -50%);
      width:28px;
      height:28px;
      border-radius:50%;
      border:0;
      background:#9ea8b3;
      color:#fff;
      font-size:18px;
      cursor:pointer;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .actions{
      display:flex;
      justify-content:flex-end;
      margin-top:20px;
    }
    .btn-save{
      background:#214b6a;
      color:#fff;
      border:0;
      padding:14px 36px;
      border-radius:26px;
      font-size:clamp(13px,2vw,14px);
      font-weight:600;
      cursor:pointer;
      box-shadow:0 14px 30px rgba(33,69,106,.16);
      transition:.2s ease;
    }
    .btn-save:hover{
      transform:translateY(-1px);
      box-shadow:0 18px 34px rgba(33,69,106,.25);
    }
    .popup-bg{
      position:fixed;
      inset:0;
      display:none;
      background:rgba(4,20,34,.45);
      align-items:center;
      justify-content:center;
      z-index:60;
    }
    .popup{
      background:#fff;
      padding:26px;
      border-radius:14px;
      width:300px;
      text-align:center;
      box-shadow:0 10px 30px rgba(0,0,0,.2);
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
    .camera-popup video {
        position: relative;
        border-radius:10px;
    }

    .camera-btn button {
        font-size: 14px;
        padding: 12px 18px;
        border-radius: 10px;
    }
    .camera-popup .popup{
      width:420px;
      max-width:85%;
      padding: 25px 25px 20px;
    }
    .camera-text{
      font-size:13px;
      color:#777
    }
    video{
      width:100%;
      border-radius:10px;
    }

    @media (max-width:768px){
      .header{
        gap:clamp(14px,8vw,70px);
      }
      .form-grid{
        grid-template-columns:1fr;
      }
      .wrap{
        border-radius:28px 28px 0 0;
      }
      .pattern.left{
      left:-20px;
      top:960px;
      }
      .pattern.right{
        right:-20px;
        bottom:920px;
      }
    }

    @media (max-width: 480px){

      html{
        font-size: 90%;
      }
      body{
        font-size: 12.5px;
      }
      .header{
        flex-direction:column;
        align-items:flex-start;
        gap:14px;
      }
      .brand img{
        width: 150px;
      }
      .preview-img{
        width:140px;
      }
      .intro{
        font-size: 13px;
      }
      .field label{
        font-size: 13px;
      }
      .muted{
        font-size: 10px;
      }
      .hero h1{
        font-size: 20px;
        line-height: 1.25;
      }
      .hero p{
        font-size: 11px;
      }
      .control{
        padding: 10px 10px;
        min-height: 36px;
      }
      .control input{
        font-size: 12.5px;
      }
      .btn-save{
        font-size: 12.5px;
        padding: 12px 24px;
      }
      .btn-add{
        font-size: 11px;
        padding: 6px 10px;
      }
      .camera-text{
      font-size:10px;
      }
      .camera-btn button {
        font-size: 12px;
        padding: 12px 18px;
        border-radius: 10px;
      }
      .preview-img{
        width: 130px;
      }
      .pattern{
        width: 65px;
      }
      .pattern.left{
        left:-20px;
        top:840px;
      }
      .pattern.right{
        right:-20px;
        bottom:800px;
      }
    }

    @media (max-width: 380px){

      html{
        font-size: 90%;
      }
      body{
        font-size: 12.5px;
      }
      .hero h1{
        font-size: 18px;
        line-height: 1.25;
      }
      .hero p{
        font-size: 11.5px;
      }
      .intro{
        font-size: 11.5px;
      }
      .field label{
        font-size: 11.5px;
      }
      .control{
        padding: 10px 10px;
        min-height: 36px;
      }
      .control input{
        font-size: 11.5px;
      }
      .muted{
        font-size: 9px;
      }
      .btn-save{
        font-size: 11.5px;
        padding: 12px 24px;
      }
      .btn-add{
        font-size: 11px;
        padding: 6px 10px;
      }
      .preview-img{
        width: 130px;
      }
      .camera-text{
        font-size:9px;
      }
    }
    </style>
</head>
<body>
  <header class="header">
    <div class="brand">
      <img src="assets/logoUmkm.png" alt="logo">
    </div>
    <div class="hero">
      <h1>Selamat Datang</h1>
      <p>di Deputi Bidang Usaha Menengah Kementerian UMKM RI</p>
    </div>
  </header>

  <main class="wrap" role="main">
    <img src="assets/pola.png" alt="pattern" class="pattern left">
    <img src="assets/pola.png" alt="pattern" class="pattern right">
    <div class="intro">Silahkan melengkapi data pada formulir di bawah ini.</div>
    <form id="guestForm" method="post" autocomplete="off" action="simpan.php?kdsatker=<?= htmlspecialchars($kdsatker) ?>">
      <input type="hidden" name="kdsatker" value="<?= htmlspecialchars($kdsatker) ?>">

      <div class="form-grid">
        <div>
          <div class="field date-wrap">
            <label for="tanggal_display">Tanggal Kedatangan</label>
            <div class="control">
              <input id="tanggal_display" type="text" placeholder="dd/mm/yyyy" class="date-display" readonly style="cursor:pointer">
              <input id="tanggal_real" name="tanggal" type="date" style="position:absolute;opacity:0;pointer-events:none;height:0;width:0">
              <img src="https://cdn-icons-png.flaticon.com/128/2948/2948088.png" class="date-icon" id="openCalendar" style="cursor:pointer">
            </div>
          </div>
          <div class="field">
            <label>Nama Lengkap</label>
            <div class="name-wrapper">
                <div class="control">
                  <input type="text" name="nama[]" placeholder="Masukkan nama lengkap sesuai identitas" id="mainName">
                </div>
                <div class="muted">Tambahkan "Nama Baru" jika Anda mengisi data untuk lebih dari satu orang.</div>
                <button type="button" class="btn-add" id="btnAddName">＋ Tambah Nama Baru</button>
            </div>
            <div id="extraNames"></div>
          </div>
          <div class="field">
            <label>Nomor Whatsapp</label>
            <div class="control">
              <div style="display:flex;align-items:center;gap:8px;">
                <div style="display:flex;align-items:center;padding:6px 8px;border-radius:8px;background:white;border:1px solid #f0f0f2;">
                  <img src="https://cdn-icons-png.flaticon.com/128/11654/11654463.png" style="width:18px;margin-right:6px">
                  <small style="color:#b33">+62</small>
                </div>
              </div>
              <input id="phone" type="tel" name="nohp" placeholder="Masukkan nomor Whatsapp aktif">
            </div>
          </div>
          <div class="field">
            <label>Alamat Email</label>
            <div class="control">
              <input type="email" name="email" placeholder="Masukkan alamat email aktif">
            </div>
          </div>
        </div>
        <div>
          <div class="field">
            <label>Instansi Asal / Alamat</label>
            <div class="control">
              <input type="text" name="instansi" placeholder="Masukkan nama instansi atau alamat asal">
            </div>
          </div>
          <div class="field">
            <label>Pihak yang Ingin Ditemui</label>
            <div class="control">
              <input type="text" name="tujuan" placeholder="Masukkan pihak yang ingin ditemui">
            </div>
          </div>
          <div class="field">
            <label>Keperluan</label>
            <div class="control">
              <input type="text" name="keperluan" placeholder="Jelaskan tujuan kunjungan">
            </div>
          </div>
          <div class="field">
            <label>Foto Pengunjung (selfie di lokasi)</label>
            <div class="muted">Gunakan kamera ponsel dan pastikan wajah serta latar terlihat jelas.</div>
            <div class="photo-box" id="photoBox" tabindex="0" role="button">
              <img src="https://cdn-icons-png.flaticon.com/128/711/711191.png" class="icon" alt="camera">
              <div class="camera-text">Ketuk untuk mengambil gambar</div>
              <input type="hidden" name="foto_data" id="fotoData">
            </div>
            <div id="previewWrap" style="margin-top:10px; display:none;">
              <img id="preview" class="preview-img" alt="preview">
              <button type="button" id="deletePhoto" class="delete-photo">✕</button>
            </div>
          </div>
        </div>
      </div>
      <div class="actions">
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </main>
  <div id="popup" class="popup-bg" role="dialog" aria-hidden="true">
    <div class="popup">
      <img src="https://cdn-icons-png.flaticon.com/128/14090/14090371.png" alt="" style="width:72px">
      <h3 style="margin-top:12px;margin-bottom:6px">Berhasil!</h3>
      <p style="color:  #596a76">Data Anda telah tersimpan.</p>
      <button onclick="closePopup()">OK</button>
    </div>
  </div>
  <div id="cameraPopup" class="popup-bg camera-popup" aria-hidden="true">
    <div class="popup">
      <button onclick="closeCamera()" style="position:absolute;right:12px;top:8px;color:#596a76;background:#f3f4f6;border-radius:50%;border:0;width:36px;height:36px;cursor:pointer">✕</button>
      <div style="position:relative;">
        <video id="cameraStream" autoplay playsinline></video>
      </div>
      <div class="camera-btn" style="display:flex;gap:8px;justify-content:center">
        <button type="button" onclick="switchCamera()">Ganti Kamera</button>
        <button type="button" onclick="takePhoto()">Ambil Gambar</button>
      </div>
      <canvas id="cameraCanvas" style="display:none"></canvas>
    </div>
  </div>
  <script>
      const tanggalDisplay = document.getElementById("tanggal_display");
      const tanggalReal = document.getElementById("tanggal_real");
      const openCalendarBtn = document.getElementById("openCalendar");

      tanggalDisplay.addEventListener("click", () => tanggalReal.showPicker());
      openCalendarBtn.addEventListener("click", () => tanggalReal.showPicker());
      tanggalReal.addEventListener("change", () => {
          if (tanggalReal.value) {
              const [y, m, d] = tanggalReal.value.split("-");
              tanggalDisplay.value = `${d}/${m}/${y}`;
          }
      });

      document.getElementById('btnAddName').addEventListener('click', function(){
        const extra = document.createElement('div');
        extra.className = 'name-extra';
        extra.innerHTML = `
          <input name="nama[]" placeholder="Masukkan nama lengkap sesuai identitas">
          <div class="small-circle" onclick="this.parentElement.remove()">−</div>`;
        document.getElementById('extraNames').appendChild(extra);
      });

      const phone = document.getElementById('phone');
      phone.addEventListener('input', () => {
        let v = phone.value.replace(/\D/g,'');
        if (v.startsWith('62')) v = v.substring(2);
        if (v.startsWith('0')) v = v.substring(1);
        phone.value = v;
      });
      const previewWrap = document.getElementById("previewWrap");
      const deletePhoto = document.getElementById("deletePhoto");

      deletePhoto.addEventListener("click", () => {
          preview.src = "";
          fotoData.value = "";
          previewWrap.style.display = "none";
          photoBox.style.display = "flex";
      });

      const photoBox = document.getElementById('photoBox');
      const cameraPopup = document.getElementById('cameraPopup');
      const cameraStream = document.getElementById('cameraStream');
      const canvas = document.getElementById('cameraCanvas');
      const fotoData = document.getElementById('fotoData');
      const preview = document.getElementById('preview');
      let currentStream = null; 
      let useFront = true;

      photoBox.addEventListener('click', openCamera);

      function openCamera(){
        cameraPopup.style.display='flex';
        startCamera();
      }

      function startCamera(){
        stopCamera();
        navigator.mediaDevices.getUserMedia({
            video:{ facingMode: useFront ? 'user' : 'environment' }
        })
        .then(s => { currentStream = s; cameraStream.srcObject = s; })
        .catch(() => { cameraPopup.style.display='none'; });
      }

      function stopCamera(){
        if(currentStream) currentStream.getTracks().forEach(t=>t.stop());
        cameraStream.srcObject=null;
      }

      function closeCamera(){
        stopCamera();
        cameraPopup.style.display='none';
      }

      function switchCamera(){
        useFront = !useFront;
        startCamera();
      }

      function takePhoto(){
        canvas.width = cameraStream.videoWidth;
        canvas.height = cameraStream.videoHeight;
        canvas.getContext('2d').drawImage(cameraStream, 0, 0);

        const data = canvas.toDataURL('image/jpeg', 0.9);

        fotoData.value = data;
        preview.src = data;

        previewWrap.style.display = "inline-block";

        photoBox.style.display = "none";

        closeCamera();
      }

      const form = document.getElementById('guestForm');
      const btnSave = document.querySelector('.btn-save');

      let isSending = false;

      form.addEventListener('submit', async function(e){
        e.preventDefault();

        if (isSending) return;
        isSending = true;

        btnSave.disabled = true;
        btnSave.innerText = "Menyimpan...";

        if (!tanggalDisplay.value.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            alert("Tanggal belum diisi atau tidak valid");
            isSending = false;
            btnSave.disabled = false;
            btnSave.innerText = "Simpan";
            return;
        }

        const fd = new FormData(form);

        try {
          const controller = new AbortController();
          const timeout = setTimeout(() => controller.abort(), 10000);

          const res = await fetch(form.action, {
            method:'POST',
            body: fd,
            signal: controller.signal
          });

          clearTimeout(timeout);

          const text = await res.text();

          if (text.trim().startsWith("OK")) {
            showPopup();
            form.reset();

            previewWrap.style.display = "none";
            preview.src = "";
            fotoData.value = "";
            photoBox.style.display = "flex";
            document.getElementById('extraNames').innerHTML = "";
          } else {
            alert("Gagal: " + text);
          }

        } catch(err){
          alert('Gagal mengirim: ' + err.message);
        }

        isSending = false;
        btnSave.disabled = false;
        btnSave.innerText = "Simpan";
      });

      function showPopup(){ document.getElementById('popup').style.display='flex'; }
      function closePopup(){ document.getElementById('popup').style.display='none'; }
    </script>
  </body> 
</html>
