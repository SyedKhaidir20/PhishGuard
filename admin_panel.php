<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;


if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = $success_message ?: 'Operation completed successfully!';
}
if (isset($_GET['error']) && $_GET['error'] == '1') {
    $error_message = $error_message ?: 'Something went wrong!';
}

unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Phishing Simulator</title>
    <link rel="stylesheet" href="styles.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <style>
/* ===== ROOT ===== */
:root {
    --primary: #1d4ed8;
    --sidebar: #0f172a;
    --bg: #f1f5f9;
    --white: #ffffff;
    --border: #e5e7eb;
    --text: #111827;
    --text-light: #6b7280;
    --success: #16a34a;
    --danger: #dc2626;
}

/* ===== BODY ===== */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    display: flex;
}

/* ===== SIDEBAR ===== */
.nav-bar {
    width: 220px;
    height: 100vh;
    background: var(--sidebar);
    display: flex;
    flex-direction: column;
    padding: 20px 10px;
    position: fixed;
}

.nav-bar button {
    background: none;
    border: none;
    color: #cbd5f5;
    text-align: left;
    padding: 12px 15px;
    margin: 5px 0;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.nav-bar button:hover {
    background: #1e293b;
    color: white;
}

.nav-bar .active {
    background: var(--primary);
    color: white;
}

.nav-bar button i {
    margin-right: 10px;
}

/* ===== MAIN CONTENT ===== */
.container {
    margin-left: 240px;
    padding: 30px;
    width: 100%;
}

/* ===== HEADER ===== */
.container h2 {
    margin-bottom: 5px;
}

.container h3 {
    margin-top: 25px;
    color: var(--text-light);
}

/* ===== CARD ===== */
form, .email-template {
    background: var(--white);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid var(--border);
    margin-top: 20px;
}

/* ===== FORM ===== */
form label {
    font-weight: 500;
    margin-top: 12px;
    display: block;
}

form input,
form select,
form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 6px;
    border: 1px solid var(--border);
    font-size: 14px;
}

form input:focus,
form select:focus,
form textarea:focus {
    border-color: var(--primary);
    outline: none;
}

/* ===== FILE UPLOAD ===== */
.file-upload-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 8px;
}

.file-upload-button {
    background: var(--primary);
    border: none;
    padding: 8px 14px;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.file-upload-button:hover {
    opacity: 0.9;
}

.file-upload-filename {
    font-size: 13px;
    color: var(--text-light);
}

/* ===== BUTTON ===== */
form button[type="submit"] {
    margin-top: 20px;
    padding: 12px;
    background: var(--success);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}

form button[type="submit"]:hover {
    opacity: 0.9;
}

/* ===== EMAIL PREVIEW ===== */
.email-template h4 {
    margin-bottom: 10px;
    color: var(--text-light);
}

#previewContent {
    background: #f9fafb;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
}

/* ===== ALERT ===== */
.success, .error {
    margin-top: 15px;
    padding: 10px;
    border-radius: 6px;
}

.success {
    background: #ecfdf5;
    color: #065f46;
}

.error {
    background: #fef2f2;
    color: #7f1d1d;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .nav-bar {
        width: 100%;
        height: auto;
        flex-direction: row;
        position: relative;
    }

    .container {
        margin-left: 0;
        padding: 20px;
    }
}
    </style>
</head>
<body>
    <div class="nav-bar">
        <button onclick="window.location.href='admin_panel.php'" class="active"><i class="fas fa-user-cog"></i> Admin Panel</button>
        <button onclick="window.location.href='dashboard.php'"><i class="fas fa-tachometer-alt"></i> Dashboard</button>
        <button onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="container">
        <h2><i class="fas fa-cogs"></i> Admin Panel - Campaign Management</h2>

        <h3><i class="fas fa-envelope-open-text"></i> Create Phishing Campaign</h3>
        <form method="POST" action="create_campaign.php" enctype="multipart/form-data" id="campaignForm">
            <label for="campaignName">Campaign Name:</label>
            <input type="text" name="campaign_name" id="campaignName" required placeholder="e.g., Q1 Security Awareness" />

            <label for="emailTemplate">Email Template:</label>
            <select name="email_template" id="emailTemplate" onchange="updateEmailPreview()">
                <option value="it">IT Department Update</option>
                <option value="hr">HR Policy Update</option>
            </select>

            <label for="emailSubject">Email Subject:</label>
            <input type="text" name="email_subject" id="emailSubject" required placeholder="Urgent: Verify Your Account" />

            <label for="targetEmails">Target Emails (one per line):</label>
            <textarea name="target_emails" id="targetEmails" rows="8" required placeholder="user1@company.com&#10;user2@company.com&#10;user3@company.com"></textarea>

            <label for="campaignDuration">Campaign Duration (days):</label>
            <input type="number" name="campaign_duration" id="campaignDuration" min="1" max="30" value="7" />

            <label>Attachment File (optional):</label>
            <div class="file-upload-wrapper">
                <button type="button" class="file-upload-button">
                    <i class="fas fa-paperclip"></i> Choose File
                </button>
                <span class="file-upload-filename" id="fileName">No file chosen</span>
                <input type="file" name="attachment" id="fileInput" accept=".txt,.pdf,.doc,.docx" />
            </div>
            <p style="font-size: 12px; color: var(--text-color-light, #666);">Supported: .txt, .pdf, .doc, .docx</p>

            <button type="submit" id="launchCampaignBtn"><i class="fas fa-rocket"></i> Launch Campaign</button>
        </form>

        <div id="emailPreview" class="email-template">
            <h4><i class="fas fa-eye"></i> Email Preview</h4>
            <div id="previewContent"></div>
        </div>

        <?php if ($success_message): ?>
            <p class="success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p class="error"><i class="fas fa-times-circle"></i> <?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <script>
        const emailTemplates = {
            it: {
                subject: "URGENT: Kemas Kini Data Peribadi Sistem Anda",
                content: `
                    <div style="font-family: Arial, sans-serif; border: 1px solid #007bff; padding: 20px; background: #e0f2f7; border-radius: 8px;">
                        <h3 style="color: #0c5460; margin-top: 0;">IT Department Notice</h3>
                        <p style="color: #333;">Dear User,</p>
                        <p style="color: #333;">Your system requires an immediate security update. Please click the link below to update your credentials and ensure continued access to company resources.</p>
                        <p style="color: #333;"><strong>Update Deadline:</strong> Today, 5:00 PM PST</p>
                        <p style="text-align: center; margin-top: 25px;">
                            <a href="https://example.com/update" style="background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                                <span style="font-weight: bold;">💻 Update System Now</span>
                            </a>
                        </p>
                        <p style="font-size: 0.9em; color: #555; margin-top: 30px;">Thank you,<br>IT Security Team</p>
                    </div>
                `
            },
            hr: {
                subject: "HR: Urgent - New Company Policy Update",
                content: `
                    <div style="font-family: Arial, sans-serif; border: 1px solid #28a745; padding: 20px; background: #e6faed; border-radius: 8px;">
                        <h3 style="color: #155724; margin-top: 0;">Human Resources Department Announcement</h3>
                        <p style="color: #333;">Dear User,</p>
                        <p style="color: #333;">We have introduced a new company policy that all employees are required to review and acknowledge. This policy covers important updates regarding remote work guidelines and data privacy.</p>
                        <p style="color: #333;"><strong>Action Required:</strong> Please review the updated policy by end of business today.</p>
                        <p style="text-align: center; margin-top: 25px;">
                            <a href="https://example.com/policy" style="background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                                <span style="font-weight: bold;">📋 Review Policy Document</span>
                            </a>
                        </p>
                        <p style="font-size: 0.9em; color: #555; margin-top: 30px;">Best regards,<br>HR Department</p>
                    </div>
                `
            }
        };

        function updateEmailPreview() {
            const template = document.getElementById('emailTemplate').value;
            const previewContent = document.getElementById('previewContent');

            if (emailTemplates[template]) {
                document.getElementById('emailSubject').value = emailTemplates[template].subject;
                previewContent.innerHTML = emailTemplates[template].content;
            }
        }

        window.confirmLogout = function() {
            Swal.fire({
                title: 'Are you sure you want to logout?',
                text: "You will need to log back in to access the admin panel.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileName');
            const targetEmailsTextarea = document.getElementById('targetEmails');
            const campaignForm = document.getElementById('campaignForm');

            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'No file chosen';
                }
            });

            campaignForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const emailListRaw = targetEmailsTextarea.value;
                const emails = emailListRaw.split('\n').map(email => email.trim()).filter(email => email !== '');
                const invalidEmails = [];
                const validEmails = [];
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emails.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Target Emails',
                        text: 'Please enter at least one email address.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                emails.forEach(email => {
                    if (!emailRegex.test(email)) {
                        invalidEmails.push(email);
                    } else {
                        validEmails.push(email);
                    }
                });

                if (invalidEmails.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email(s) Found',
                        html: `The following email addresses are not in a valid format or are not on new lines:<br>
                                <strong>${invalidEmails.join('<br>')}</strong><br><br>
                                Please ensure each email is on a new line and correctly formatted.`,
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                

                targetEmailsTextarea.value = validEmails.join('\n');

                Swal.fire({
                    title: 'Launching Campaign...',
                    html: 'Please wait while emails are being prepared and sent.',
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                campaignForm.submit();
            });

            updateEmailPreview();

            <?php if (isset($success_message)): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Campaign Launched!',
                    text: 'Campaign has been successfully launched!',
                    showConfirmButton: false,
                    timer: 3000
                });
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Campaign Launch Failed!',
                    text: '<?= htmlspecialchars($error_message) ?>',
                    confirmButtonColor: '#dc3545'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>