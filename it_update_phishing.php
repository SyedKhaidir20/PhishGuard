<?php
include 'config.php';

$campaign_id = isset($_GET['campaign_id']) ? $_GET['campaign_id'] : $_POST['campaign_id'];

if(isset($_GET['email'])) {
    $email_id = $_GET['email'];
    $pdo->prepare("UPDATE simulation_results SET email_opened = 1, link_clicked = 1 WHERE user_email = ? AND campaign_id = ?")->execute([$email_id,$campaign_id]);
}

if(isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    $email_id = $_GET['email'];
    $pdo->prepare("UPDATE simulation_results SET file_opened = 1 WHERE user_email = ? AND campaign_id = ?")->execute([$email_id,$campaign_id]);
}

$formSubmitted = false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pdo->prepare("UPDATE simulation_results SET link_clicked = 1, data_submitted = 1 WHERE user_email = ? AND campaign_id = ?")->execute([$email,$campaign_id]);
    
    $pdo->prepare("UPDATE simulation_results SET risk_level = 'high' WHERE user_email = ? AND campaign_id = ?")->execute([$email,$campaign_id]);
    
    $formSubmitted = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unit Teknologi Maklumat - Kemas Kini Keselamatan Sistem </title>
  <style>
/* ===== BODY ===== */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

/* ===== CONTAINER ===== */
.container {
    max-width: 650px;
    margin: 50px auto;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}

/* ===== HEADER ===== */
.header {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    padding: 20px;
}

.header h1 {
    font-size: 18px;
    margin: 0;
    color: #1f2937;
}

.security-badge {
    font-size: 12px;
    color: #6b7280;
    margin-top: 5px;
}

/* ===== CONTENT ===== */
.content {
    padding: 25px;
}

/* ===== TEXT BLOCK ===== */
.alert-box {
    background: #f9fafb;
    border-radius: 6px;
    padding: 15px;
    font-size: 14px;
    color: #374151;
    margin-bottom: 20px;
}

/* ===== REMOVE SCARY RED ===== */
.urgent-notice {
    background: #f9fafb;
    border-radius: 6px;
    padding: 15px;
    font-size: 14px;
    color: #374151;
    margin-bottom: 15px;
}

/* ===== FORM ===== */
h3 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #1f2937;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-size: 13px;
    color: #374151;
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    margin-top: 5px;
    font-size: 14px;
}

input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 1px #2563eb;
}

/* ===== BUTTON ===== */
.submit-btn {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 10px;
}

.submit-btn:hover {
    background: #1d4ed8;
}

/* ===== FOOTER ===== */
.footer {
    text-align: center;
    font-size: 12px;
    padding: 15px;
    background: #f9fafb;
    color: #6b7280;
}

/* ===== RESULT ===== */
#revealMessage {
    background: #f3f4f6;
    border-radius: 6px;
    padding: 20px;
    margin-top: 20px;
}

.security-tips {
    background: #eef2ff;
    padding: 15px;
    border-radius: 6px;
    margin-top: 10px;
}

.quiz-link {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background: #2563eb;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}

.quiz-link:hover {
    background: #1d4ed8;
}
  </style>
</head>
<body>
  
<script>

// ===============================
// 🚨 HTTP SECURITY WARNING POPUP
// ===============================
window.addEventListener("load", function () {

    // Check kalau bukan HTTPS
    if (window.location.protocol !== "https:") {

        let userChoice = confirm(
            "⚠️ AMARAN KESELAMATAN!\n\n" +
            "Laman web ini TIDAK menggunakan sambungan selamat (HTTPS).\n" +
            "Maklumat anda mungkin boleh dicuri oleh pihak ketiga.\n\n" +
            "Adakah anda ingin teruskan?"
        );

        if (!userChoice) {
            // kalau user tekan cancel → keluar dari page
            window.location.href = "https://www.google.com";
        }
    }

});
</script>

  <div class="container">
    <div class="header">
      <h1>🖥️ Unit Teknologi Maklumat - Kemas Kini Keselamatan Sistem </h1>
      <div class="security-badge">🔒 Sambungan Selamat</div>
    </div>

      <div class="urgent-notice">
        <h3>🚨 SEGERA: Kemas Kini Sistem Mandatori Diperlukan</h3>
        <p><strong>Akses sistem anda akan digantung jika kemas kini ini tidak selesai secepat mungkin.</strong></p>
      </div>

      <div class="alert-box">
        <h4>📋 Notis Kemas Kini Sistem</h4>
        <p>Tuan/Puan yang dihormati,</p>
        <p>Portal rasmi telah diserang ancaman siber. Disebabkan ancaman keselamatan baru-baru ini, semua pengguna mesti mengemas kini data peribadi sistem mereka dengan segera.</p>
        <p><strong>Akibat:</strong> Kegagalan untuk melengkapkan kemas kini ini akan mengakibatkan penggantungan akaun sementara.</p>
      </div>

      <form id="updateForm" method="POST" action="it_update_phishing.php">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
        <input type="hidden" name="campaign_id" value="<?= $campaign_id ?>">
        
        <h3>🔐 Kemas kini Data Peribadi Anda</h3>
        
        <div class="form-group">
          <label for="employeeId">ID Pengguna:</label>
          <input type="text" id="employeeId" name="employeeId" required placeholder="Masukkan ID Pengguna Anda">
        </div>

        <div class="form-group">
          <label for="email">Email Pengguna:</label>
          <input type="email" id="email" name="email" required placeholder="Masukkan Email Pengguna Anda">
        </div>

        <div class="form-group">
          <label for="currentPassword">Kata Laluan Semasa:</label>
          <input type="password" id="currentPassword" name="currentPassword" required placeholder="Masukkan Kata Laluan Semasa Anda">
        </div>

        <div class="form-group">
          <label for="newPassword">Kata Laluan Baru:</label>
          <input type="password" id="newPassword" name="newPassword" required placeholder="Masukkan Kata Laluan Baru Anda">
        </div>

        <div class="form-group">
          <label for="confirmPassword">Sahkan Kata Laluan Baharu:</label>
          <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Sahkan Kata Laluan Baharu Anda">
        </div>

        <button type="submit" class="submit-btn">🔄 Kemas kini Data Peribadi Anda</button>
      </form>

      <?php if($formSubmitted): ?>
      <div id="revealMessage" style="display: block;">
        <h3>🚨 Data Anda Telah Dicuri!</h3>
        <p><strong>Ini adalah simulasi pancingan data untuk menguji kesedaran keselamatan anda.</strong></p>
        <p>Dalam serangan sebenar, data yang baru anda masukkan akan terbongkar dan boleh digunakan untuk mengakses akaun dan sistem syarikat atau institut anda.</p>
        
        <div class="security-tips">
          <h4>🛡️ Cara Melindungi Diri Anda:</h4>
          <ul>
            <li><strong>Verify the sender:</strong> Always check if urgent IT requests come from official IT channels</li>
            <li><strong>Check the URL:</strong> Look for misspellings or suspicious domains</li>
            <li><strong>Don't click email links:</strong> Navigate to official company portals directly</li>
            <li><strong>Contact IT directly:</strong> When in doubt, call or visit the IT department</li>
            <li><strong>Look for red flags:</strong> Urgent deadlines, threats of suspension, and pressure tactics</li>
          </ul>
        </div>
        
        <a href="phishing_quiz.php?email=<?= urlencode($_POST['email'] ?? '') ?>&campaign_id=<?= $campaign_id ?>" class="quiz-link">📝 Take the Security Awareness Quiz</a>
        
        <p><em>📊 This simulation data has been logged for security training purposes. Your actual credentials were not compromised.</em></p>
      </div>
      <script>

        document.getElementById('updateForm').style.display = 'none';
        document.getElementById('countdown').style.display = 'none';
      </script>
      <?php endif; ?>
    </div>

    <div class="footer">
      <p>🔒 Unit Teknologi Maklumat | Portal Kemas Kini Sistem </p>
      <p>Untuk sokongan teknikal, hubungi: +609-840 0846/0849 (Unit Teknologi Maklumat PSMZA)</p>
    </div>
  </div>

  <script>
    window.onload = function() {
      startCountdown();
      
      <?php if(!$formSubmitted): ?>

      document.getElementById('updateForm').addEventListener('submit', function(event) {
        event.preventDefault();
        

        this.submit();
      });
      <?php endif; ?>
    };
  </script>
</body>
</html>