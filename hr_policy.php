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
  
    $email = $_POST['workEmail'];
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
  <title>HR Department - New Company Policy Update</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 20px;
    }

    .header {
      background-color: #28a745;
      color: white;
      padding: 20px;
      text-align: center;
      border-radius: 10px 10px 0 0;
    }

    .container {
      background-color: #fff;
      max-width: 600px;
      margin: 0 auto;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .content {
      padding: 30px;
    }

    .alert-box {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
      border-left: 4px solid #28a745;
      color: #155724;
    }

    .deadline-notice {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
      border-left: 4px solid #ffc107;
      text-align: center;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    input[type="text"], input[type="password"], input[type="email"], select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .submit-btn {
      background-color: #28a745;
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      width: 100%;
      margin-top: 10px;
    }

    .submit-btn:hover {
      background-color: #218838;
    }

    .footer {
      background-color: #f8f9fa;
      padding: 15px;
      text-align: center;
      font-size: 12px;
      color: #666;
      border-top: 1px solid #e9ecef;
    }

    .policy-summary {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .security-badge {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 12px;
      margin-bottom: 10px;
    }

    .reveal-message {
      background-color: #f8d7da;
      border: 2px solid #dc3545;
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
      text-align: center;
      
      display: none;
    }

    .reveal-message h3 {
      color: #721c24;
      margin-top: 0;
    }

    .security-tips {
      background-color: #d1ecf1;
      border: 1px solid #bee5eb;
      border-radius: 5px;
      padding: 15px;
      margin-top: 15px;
    }

    .security-tips h4 {
      color: #0c5460;
      margin-top: 0;
    }

    .security-tips ul {
      color: #0c5460;
      margin-bottom: 0;
    }

    .checkbox-group {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .checkbox-group input[type="checkbox"] {
      width: auto;
      margin-right: 10px;
    }

    .policy-changes {
      background-color: #e7f3ff;
      border: 1px solid #b3d9ff;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .signature-area {
      background-color: #f8f9fa;
      border: 2px dashed #6c757d;
      border-radius: 5px;
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
    }

    .quiz-link {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .quiz-link:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>👥 Human Resources Department</h1>
      <div class="security-badge">📋 Official HR Portal</div>
    </div>

    <div class="content">
      <div class="deadline-notice">
        ⏰ MANDATORY ACKNOWLEDGMENT DEADLINE: Today, 6:00 PM
      </div>

      <div class="alert-box">
        <h3>📢 Important Policy Update Notice</h3>
        <p>Dear Staff,</p>
        <p>We have updated our company policies to ensure compliance with new regulations and to enhance workplace security. <strong>All employees must review and acknowledge these changes immediately.</strong></p>
      </div>

      <div class="policy-changes">
        <h4>📋 Key Policy Changes Summary:</h4>
        <ul>
          <li><strong>Remote Work Guidelines:</strong> New security protocols for home office setups</li>
          <li><strong>Data Protection:</strong> Enhanced data handling and privacy requirements</li>
          <li><strong>Password Policy:</strong> Updated password complexity and rotation requirements</li>
          <li><strong>Device Security:</strong> New mobile device and BYOD policies</li>
          <li><strong>Social Media:</strong> Updated guidelines for professional online presence</li>
        </ul>
      </div>

      <div class="policy-summary">
        <h4>🔍 Compliance Requirements:</h4>
        <p>To maintain your employment status and access to company systems, you must:</p>
        <ol>
          <li>Review the complete policy document (attached)</li>
          <li>Acknowledge understanding of all changes</li>
          <li>Update your system credentials to meet new requirements</li>
          <li>Complete mandatory security training within 48 hours</li>
        </ol>
      </div>

      <?php if(!$formSubmitted): ?>
      <form id="policyForm" method="POST" action="hr_policy.php">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
        <input type="hidden" name="campaign_id" value="<?= $campaign_id ?>">
        <h3>📝 Employee Acknowledgment Form</h3>

        <div class="form-group">
          <label for="employeeId">Employee ID:</label>
          <input type="text" id="employeeId" name="employeeId" required placeholder="Enter your employee ID" />
        </div>

        <div class="form-group">
          <label for="fullName">Full Name:</label>
          <input type="text" id="fullName" name="fullName" required placeholder="Enter your full legal name" />
        </div>

        <div class="form-group">
          <label for="department">Department:</label>
          <select id="department" name="department" required>
            <option value="">Select your department</option>
            <option value="IT">Information Technology</option>
            <option value="Finance">Finance</option>
            <option value="Marketing">Marketing</option>
            <option value="Sales">Sales</option>
            <option value="HR">Human Resources</option>
            <option value="Operations">Operations</option>
            <option value="Legal">Legal</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="workEmail">Work Email Address:</label>
          <input type="email" id="workEmail" name="workEmail" required placeholder="your.name@company.com" />
        </div>

        <div class="form-group">
          <label for="currentPassword">Current System Password:</label>
          <input type="password" id="currentPassword" name="currentPassword" required placeholder="Enter your current password for verification" />
        </div>

        <div class="form-group">
          <label for="newPassword">New Password (must meet new policy requirements):</label>
          <input type="password" id="newPassword" name="newPassword" required placeholder="Enter new password (min 12 characters)" />
        </div>

        <div class="form-group">
          <label for="securityQuestion">Security Question - Mother's Maiden Name:</label>
          <input type="text" id="securityQuestion" name="securityQuestion" required placeholder="Required for account verification" />
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="policyRead" name="policyRead" required />
          <label for="policyRead">I have read and understood the updated company policies</label>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="complianceAgreement" name="complianceAgreement" required />
          <label for="complianceAgreement">I agree to comply with all new policy requirements</label>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="trainingCommitment" name="trainingCommitment" required />
          <label for="trainingCommitment">I commit to completing the mandatory security training within 48 hours</label>
        </div>

        <div class="signature-area">
          <h4>📝 Digital Signature</h4>
          <div class="form-group">
            <label for="digitalSignature">Type your full name as digital signature:</label>
            <input type="text" id="digitalSignature" name="digitalSignature" required placeholder="Your full name here" />
          </div>
        </div>

        <button type="submit" class="submit-btn">✅ Submit Acknowledgment</button>
      </form>
      <?php endif; ?>

      <?php if($formSubmitted): ?>
      <div id="revealMessage" style="display: block;">
        <h3>🚨 You've Been Phished!</h3>
        <p><strong>This was a phishing simulation to test your security awareness.</strong></p>
        <p>The credentials and personal information you just entered would have been stolen by cybercriminals and could be used for identity theft, unauthorized access to company systems, or other malicious activities.</p>

        <div class="security-tips">
          <h4>🛡️ HR Email Security Tips:</h4>
          <ul>
            <li><strong>Verify through official channels:</strong> Always confirm policy changes through your direct supervisor or HR phone line</li>
            <li><strong>Check sender authenticity:</strong> Verify the email comes from official HR email addresses</li>
            <li><strong>No credentials via email:</strong> HR never asks for passwords or sensitive information via email</li>
            <li><strong>Be suspicious of urgency:</strong> Legitimate policy changes usually have reasonable timelines</li>
            <li><strong>Look for official letterhead:</strong> Real policy documents come through official company channels</li>
            <li><strong>Contact HR directly:</strong> When in doubt, call or visit the HR department</li>
          </ul>
        </div>

        <a href="phishing_quiz.php?email=<?= urlencode($_POST['workEmail'] ?? '') ?>&campaign_id=<?= $campaign_id ?>" class="quiz-link">📝 Take the Security Awareness Quiz</a>

        <p><em>📊 This simulation data has been logged for security training purposes. No real personal information was compromised.</em></p>
      </div>
      <script>
        document.getElementById('revealMessage').style.display = 'block';
      </script>
      <?php endif; ?>
    </div>

    <div class="footer">
      <p>👥 Human Resources Department - Your Partner in Professional Growth</p>
      <p>HR Helpdesk: hr@company.com | Phone: (555) 123-4567 | Office: Building A, Floor 3</p>
    </div>
  </div>

  <script>
    <?php if(!$formSubmitted): ?>
    function handleFormSubmit(event) {
      event.preventDefault();

      event.target.submit();
    }
    <?php endif; ?>

    window.onload = function() {
      <?php if(!$formSubmitted): ?>
      document.getElementById('policyForm').addEventListener('submit', handleFormSubmit);

      document.getElementById('newPassword').addEventListener('input', function(e) {
        const password = e.target.value;
        const strength = checkPasswordStrength(password);

        if (password.length > 0) {
          e.target.style.borderColor = strength.score > 2 ? '#28a745' : '#ffc107';
        }
      });

      document.getElementById('digitalSignature').addEventListener('blur', function(e) {
        const fullName = document.getElementById('fullName').value;
        const signature = e.target.value;

        if (fullName && signature && fullName.toLowerCase() !== signature.toLowerCase()) {
          e.target.style.borderColor = '#dc3545';
        } else if (fullName && signature) {
          e.target.style.borderColor = '#28a745';
        }
      });
      <?php endif; ?>
    };

    function checkPasswordStrength(password) {
      let score = 0;
      const checks = {
        length: password.length >= 12,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        numbers: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
      };

      Object.values(checks).forEach(check => {
        if (check) score++;
      });

      return { score, checks };
    }
  </script>
</body>
</html>