<?php
    include "koneksi.php";

    function nullIfEmpty($v){
        $v = trim($v ?? "");
        return ($v === "") ? null : $v;
    }
    date_default_timezone_set("Asia/Jakarta");

    $tanggal   = $_POST['tanggal'] ?? date("Y-m-d");
    $email     = nullIfEmpty($_POST['email'] ?? null);
    $instansi  = nullIfEmpty($_POST['instansi'] ?? null);
    $nohp      = nullIfEmpty($_POST['nohp'] ?? null);
    $tujuan    = nullIfEmpty($_POST['tujuan'] ?? null);
    $keperluan = nullIfEmpty($_POST['keperluan'] ?? null);
    $kdsatker = $_GET['kdsatker'] ?? '694762';
    $kdsatker = preg_replace('/[^0-9]/', '', $kdsatker);
    if ($kdsatker === '') $kdsatker = '694762';

    $nama_list = $_POST['nama'] ?? [];

    if ($nohp !== null) {
        $nohp = preg_replace('/\D/', '', $nohp);
        if (substr($nohp,0,2)==="62") $nohp = substr($nohp,2);
        if (substr($nohp,0,1)==="0")  $nohp = substr($nohp,1);
        $nohp = "62".$nohp;
    }

    $foto_file = null;
    if (!empty($_POST['foto_data'])) {

        $foto_data = str_replace('data:image/jpeg;base64,','',$_POST['foto_data']);
        $foto_data = str_replace(' ', '+', $foto_data);
        $imageData = base64_decode($foto_data);

        if (!is_dir("uploads")) mkdir("uploads", 0777, true);

        $foto_file = time().".jpg";
        file_put_contents("uploads/".$foto_file, $imageData);
    }

    $feedback_links = [];

    $stmt = $conn->prepare("
        INSERT INTO tamu
        (tanggal,email,nama,instansi,nohp,tujuan,keperluan,foto,feedback_token,feedback_status,kdsatker)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    foreach ($nama_list as $nama){

        $nama = nullIfEmpty($nama);
        $token = bin2hex(random_bytes(16));
        $status = "pending";

        $stmt->bind_param(
        "sssssssssss",
        $tanggal,$email,$nama,$instansi,$nohp,
        $tujuan,$keperluan,$foto_file,$token,$status,$kdsatker
    );

        $stmt->execute();

        $feedback_links[] = [
            "nama" => $nama,
            "link" => "http://localhost/bukut/feedback.php?token=".$token
        ];
    }

    $stmt->close();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; 
        $mail->SMTPAuth = true;
        $mail->Username = "pinkaananda@gmail.com";
        $mail->Password = "eandkppfcivaoadp";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom("pinkaananda@gmail.com", "Deputi Bidang Usaha Menengah");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Terima kasih telah mengisi Buku Tamu";

        $html_feedback = '<table style="width:100%; border-collapse: collapse; margin-top:15px;">';

    foreach ($feedback_links as $f) {
        $html_feedback .= '
            <tr>
                <td style="padding:8px; border-bottom:1px solid #ddd; width:35%; color:#333;">'
                    . htmlspecialchars($f["nama"]) .
                '</td>
                <td style="padding:8px; border-bottom:1px solid #ddd;">
                    <a href="'.$f["link"].'" 
                    style="color:#0056b3; text-decoration:none;">'
                    .$f["link"].
                    '</a>
                </td>
            </tr>';
    }

    $html_feedback .= '</table>';

        $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Buku Tamu</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f6fa;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: auto;
                    background: #ffffff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                .header {
                    color: #ffffff;
                    text-align: center;
                    border-radius: 10px 10px 0 0;
                    font-size: 20px;
                    font-weight: bold;
                }
                .title {
                    font-size: 22px;
                    margin-bottom: 5px;
                    text-align: center;
                    font-weight: bold;
                }
                .subtitle {
                    font-size: 14px;
                    margin-bottom: 20px;
                    text-align: center;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                table td {
                    padding: 10px;
                    border-bottom: 1px solid #dddddd;
                }
                table td:first-child {
                    font-weight: bold;
                    width: 35%;
                    color: #333;
                }
                .footer {
                    text-align: center;
                    margin-top: 25px;
                    font-size: 14px;
                    color: #777;
                }
            </style>
            </head>

            <body>
            <div style="text-align:center;">
                    <img src="assets/header.png"
                        alt="Header"
                        style="width:100%; max-width:600px; display:block;">
                </div>
                <div class="container">
                    <table>
                        <tr>
                            <td>Tanggal</td><td>'.$tanggal.'</td>
                        </tr>
                        <tr>
                            <td>Nama</td><td>'.implode("<br>", $nama_list).'</td>
                        </tr>
                        <tr>
                            <td>Email</td><td>'.$email.'</td>
                        </tr>
                        <tr>
                            <td>No HP</td><td>'.$nohp.'</td>
                        </tr>
                        <tr>
                            <td>Instansi</td><td>'.$instansi.'</td>
                        </tr>
                        <tr>
                            <td>Yang Ingin Ditemui</td><td>'.$tujuan.'</td>
                        </tr>
                        <tr>
                            <td>Keperluan</td><td>'.$keperluan.'</td>
                        </tr>
                        <tr>
                            <td>Foto</td><td>'.$foto_file.'</td>
                        </tr>
                    </table>
                        <p>Kami mengharapkan kesediaan Anda untuk memberikan feedback setelah kunjungan melalui tautan yang tertera di bawah ini.</p>
                        '.$html_feedback.'
                        <p style = "margin-top:10px; font-size:14px; color:#777; opacity: 0.5"> E-mail ini dibuat otomatis mohon tidak membalas. Terima kasih.</p>
                        <p style="text-align: center; margin-top:10px; font-size:12px; color:black;">
                            Salam Hormat,<br>Deputi Bidang Usaha Menengah
                        </p>
                    <div style="text-align:center; margin-top:25px;">
                        <img src="assets/footer.png"
                            alt="Footer"
                            style="width:100%; max-width:600px; display:block;">
                        </a>
                    </div>
                </div>
            </body>
            </html>';
        if ($foto_file) {
                $mail->addAttachment("uploads/".$foto_file);
            }
            $mail->send();
        } catch (Exception $e) {
            }
    echo "OK";
?>
