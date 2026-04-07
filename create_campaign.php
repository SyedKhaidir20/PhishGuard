<?php
include 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$emailTemplates = [
    'it' => [
        'subject' => "URGENT: Kemas Kini Keselamatan Sistem Wajib",
		'content' => '
			<div>
				<hr>
				<p>Assalamualaikum dan Salam Malaysia Madani,</p>
				<p>Tuan/Puan,</p>
				<p>Dengan segala hormatnya merujuk kepada perkara di atas.</p>
				<p><strong>2.</strong> Pasukan keselamatan kami telah mengesan kelemahan dalam sistem korporat yang memerlukan perhatian segera. Untuk mengekalkan integriti sistem dan melindungi data pengguna, anda mesti melengkapkan kemas kini keselamatan wajib.</p>
				<p>Sila klik pautan di bawah untuk mengakses portal kemas kini keselamatan dan ikuti arahan:</p>
				<p><a href="[PHISHING_LINK_PLACEHOLDER]"><strong>KEMAS KINI DATA</strong></a></p>
				<p>Sila semak garis panduan keselamatan yang dilampirkan: <a href="[ATTACHMENT_LINK_PLACEHOLDER]"><strong>Muat Turun Lampiran</strong></a></p>
				<p><strong>Nota:</strong> Kegagalan melengkapkan kemas kini ini akan mengakibatkan penggantungan sementara akses akaun anda.</p>
				<p>Sekian, Terima kasih.</p>
				<p><strong>"EKOSISTEM KONDUSIF KERJA PRODUKTIF"<br>"MALAYSIA MADANI"<br>"BERKHIDMAT UNTUK NEGARA"</strong></p>
				<p><strong>UnitTeknologiMaklumat</strong><br>PoliteknikSultanMizanZainalAbidin<br>Samb. 0846/0849</p>
				<hr>
				<p>© '.date('Y').' UnitTeknologiMaklumat. Hak Cipta Terpelihara.</p>
				<p>Ini adalah mesej automatik - sila jangan balas</p>
				<h4 style="color:#FF0000; font-weight:bold;">PERINGATAN</h4>
				<p>Pengguna Perkhidmatan MyGovUC adalah bertanggungjawab melindungi kerahsiaan data/maklumat Rahsia Rasmi Kerajaan. Adalah diingatkan agar pengguna sentiasa peka dengan SEMUA peraturan, arahan keselamatan dan pekeliling semasa yang berkuatkuasa bagi semua pengendalian data/maklumat Rahsia Rasmi Kerajaan yang berkaitan.</p>
			</div>
		'
    ],
    'hr' => [
        'subject' => "TINDAKAN DIPERLUKAN: Kemas Kini Dasar Syarikat - Pengakuan Segera Diperlukan",
		'content' => '
			<div>
				<h2>Jabatan Sumber Manusia</h2>
				<hr>
				<p>Assalamualaikum dan Salam Malaysia Madani,</p>
				<p>Tuan/Puan,</p>
				<p>Dengan segala hormatnya merujuk kepada perkara di atas.</p>
				<p><strong>2.</strong> Kami telah melaksanakan kemas kini penting kepada dasar syarikat untuk memastikan pematuhan dengan peraturan baharu dan untuk meningkatkan keselamatan tempat kerja. Semua pekerja dikehendaki mengkaji dan mengakui perubahan ini.</p>
				<p>Tarikh Akhir Pengakuan: Hari ini, EOD</p>
				<p>Sila klik pautan di bawah untuk mengkaji kemas kini dasar dan melengkapkan borang pengakuan:</p>
				<p><a href="[PHISHING_LINK_PLACEHOLDER]">Kaji Kemas Kini Dasar</a></p>
				<p>Sila semak garis panduan keselamatan yang dilampirkan: <a href="[ATTACHMENT_LINK_PLACEHOLDER]"><strong>Muat Turun Lampiran</strong></a></p>
				<p><strong>Penting:</strong> Pengakuan anda adalah wajib untuk mengekalkan status pekerjaan aktif.</p>
				<p>Sekian, Terima kasih.</p>
				<p><strong>EKOSISTEM KONDUSIF KERJA PRODUKTIF<br>MALAYSIA MADANI<br>BERKHIDMAT UNTUK NEGARA</strong></p>
				<p>(MARIAH BINTI JAAFAR)<br>Jabatan Sumber Manusia<br>Politeknik Sultan Mizan Zainal Abidin<br>Samb. 5555</p>
				<hr>
				<p>© '.date('Y').' Jabatan Sumber Manusia. Hak Cipta Terpelihara.</p>
				<p>Untuk pertanyaan, hubungi HR di hr@psmza.edu.my atau ext. 5555</p>
				<h4 style="color:#c00; font-weight:bold;">PERINGATAN</h4>
				<p>Pengguna Perkhidmatan MyGovUC adalah bertanggungjawab melindungi kerahsiaan data/maklumat Rahsia Rasmi Kerajaan. Adalah diingatkan agar pengguna sentiasa peka dengan SEMUA peraturan, arahan keselamatan dan pekeliling semasa yang berkuatkuasa bagi semua pengendalian data/maklumat Rahsia Rasmi Kerajaan yang berkaitan.</p>
			</div>
		'
    ]
];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO campaigns (name, template, subject, duration) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['campaign_name'],
            $_POST['email_template'],
            $_POST['email_subject'],
            $_POST['campaign_duration']
        ]);
        $campaign_id = $pdo->lastInsertId();

        $emails = explode("\n", $_POST['target_emails']);
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails);

        $stmt_targets = $pdo->prepare("INSERT INTO campaign_targets (campaign_id, email) VALUES (?, ?)");
        foreach($emails as $email) {
            $stmt_targets->execute([$campaign_id, $email]);
        }

        $file_path = null;
        $file_name = null;
        if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = basename($_FILES['attachment']['name']);

            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $unique_file_name = uniqid('attachment_', true) . '.' . $file_extension;
            $file_path = $upload_dir . $unique_file_name;

            if(!move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
                throw new Exception("Failed to move uploaded file.");
            }

            $stmt_files = $pdo->prepare("INSERT INTO campaign_files (campaign_id, file_name, file_path) VALUES (?, ?, ?)");
            $stmt_files->execute([$campaign_id, $file_name, $file_path]);
        }

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'unitteknologimaklumatpsmza@gmail.com';
        $mail->Password   = 'gxpjodfsbbuutbcv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('unitteknologimaklumat@gmail.com', 'Unit Teknologi Maklumat');
        $mail->isHTML(true);
        $mail->Subject = $_POST['email_subject'];

        $template = $_POST['email_template'];
        $baseEmailContent = $emailTemplates[$template]['content'];


        if($file_path) {
            $mail->addAttachment($file_path, $file_name);
        }


        $stmt_results = $pdo->prepare("INSERT INTO simulation_results (campaign_id, user_email) VALUES (?, ?)");

        foreach($emails as $email) {
            $mail->clearAddresses();
            $mail->addAddress($email);



            if($_POST['email_template'] == 'it') {
                $phishing_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .
                                 "/it_update_phishing.php?email=" . urlencode($email) .
                                 "&campaign_id=" . urlencode($campaign_id);
            } else {
                $phishing_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .
                                 "/hr_policy.php?email=" . urlencode($email) .
                                 "&campaign_id=" . urlencode($campaign_id);
            }   

            $current_directory = dirname($_SERVER['PHP_SELF']);
            if ($current_directory == DIRECTORY_SEPARATOR) {
                $current_directory = '';
            }


            if ($file_path) {
                $file_tracking_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .
                                      "/track_file.php?email=" . urlencode($email) .
                                      "&campaign_id=" . urlencode($campaign_id) .
                                      "&file=" . urlencode(basename($file_path));
            }


            $emailBodyForRecipient = str_replace(
                '[PHISHING_LINK_PLACEHOLDER]',
                $phishing_link,
                $baseEmailContent
            );

            $emailBodyForRecipient = str_replace(
                '[ATTACHMENT_LINK_PLACEHOLDER]',
                $file_tracking_link,
                $emailBodyForRecipient
            );

            $mail->Body = $emailBodyForRecipient;



            $stmt_results->execute([$campaign_id, $email]);

            $mail->send();
        }

        $pdo->commit();
        $_SESSION['success_message'] = "Campaign '" . htmlspecialchars($_POST['campaign_name']) . "' launched successfully!";
        header("Location: admin_panel.php");
        exit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error_message'] = "Campaign launch failed: " . $e->getMessage();

        error_log("PHPMailer error in create_campaign.php: " . $e->getMessage());
        header("Location: admin_panel.php");
        exit();
    }
}
?>