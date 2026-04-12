<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$success_message = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $question = $_POST['question'];
        $options = $_POST['options'];
        $correct = $_POST['correct'];

        // insert question
        $stmt = $pdo->prepare("INSERT INTO questions (question) VALUES (?)");
        $stmt->execute([$question]);

        $qid = $pdo->lastInsertId();

        // insert options
        foreach ($options as $i => $opt) {
            $is_correct = ($i == $correct) ? 1 : 0;

            $stmt = $pdo->prepare("
                INSERT INTO options (question_id, option_text, is_correct)
                VALUES (?, ?, ?)
            ");

            $stmt->execute([$qid, $opt, $is_correct]);
        }

        $success_message = "Question added successfully!";

    } catch (Exception $e) {
        $error_message = "Something went wrong!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Question</title>

<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

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
    height: 100vh;
    overflow: hidden;
}

/* ===== SIDEBAR ===== */
.nav-bar {
    width: 240px;
    background: var(--sidebar);
    display: flex;
    flex-direction: column;
    padding: 20px 10px;
    transition: 0.3s;
}

/* OPTIONAL: collapse sidebar */
.nav-bar.collapsed {
    width: 70px;
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
    width: 100%;
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

/* ===== MAIN CONTENT (FULL WIDTH) ===== */
.container {
    flex: 1; /* penting untuk full screen */
    padding: 30px;
    overflow-y: auto;
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
    border-radius: 12px;
    border: 1px solid var(--border);
    margin-top: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
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
    transition: 0.2s;
}

form input:focus,
form select:focus,
form textarea:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px rgba(29,78,216,0.1);
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
    body {
        flex-direction: column;
    }

    .nav-bar {
        width: 100%;
        flex-direction: row;
        overflow-x: auto;
    }

    .container {
        padding: 20px;
    }
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="nav-bar">
    <button onclick="window.location.href='admin_panel.php'">
        <i class="fas fa-user-cog"></i> Admin Panel
    </button>

    <button onclick="window.location.href='dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </button>

    <button class="active">
        <i class="fas fa-question-circle"></i> Add Question
    </button>

    <button onclick="window.location.href='manage_questions.php'">
        <i class="fas fa-list"></i> Manage Questions
    </button>

    <button onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt"></i> Logout
    </button>
</div>

<!-- CONTENT -->
<div class="container">

    <h2><i class="fas fa-plus"></i> Add Question</h2>

    <h3><i class="fas fa-edit"></i> Create New Question</h3>

    <form method="POST">

        <label>Question:</label>
        <input type="text" name="question" required placeholder="Enter question">

        <label>Option 1:</label>
        <input type="text" name="options[]" required placeholder="Option 1">

        <label>Option 2:</label>
        <input type="text" name="options[]" required placeholder="Option 2">

        <label>Option 3:</label>
        <input type="text" name="options[]" required placeholder="Option 3">

        <label>Option 4:</label>
        <input type="text" name="options[]" required placeholder="Option 4">

        <label>Correct Answer:</label>
        <select name="correct">
            <option value="0">Option 1</option>
            <option value="1">Option 2</option>
            <option value="2">Option 3</option>
            <option value="3">Option 4</option>
        </select>

        <button type="submit">
            <i class="fas fa-save"></i> Save Question
        </button>

    </form>

    <?php if ($success_message): ?>
        <p class="success"><i class="fas fa-check-circle"></i> <?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <p class="error"><i class="fas fa-times-circle"></i> <?= $error_message ?></p>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: 'Anda pasti nak logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}
</script>

</body>
</html>