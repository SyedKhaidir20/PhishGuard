<?php
include 'config.php';

/* ===== AMBIL SOALAN DARI DATABASE ===== */
$questions = [];

$stmt = $pdo->query("SELECT * FROM questions");

while ($row = $stmt->fetch()) {

    $q = [
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => []
    ];

    $opt = $pdo->prepare("SELECT * FROM options WHERE question_id=?");
    $opt->execute([$row['id']]);

    while ($o = $opt->fetch()) {
        $q['options'][] = $o['option_text'];
    }

    $questions[] = $q;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Security Awareness Quiz</title>

<style>
body {
    font-family: 'Segoe UI';
    background: #f1f5f9;
    margin: 0;
}

.container {
    max-width: 800px;
    margin: auto;
    padding: 30px;
}

h1 {
    text-align: center;
    color: #1d4ed8;
}

/* QUESTION CARD */
.question {
    background: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

/* OPTION */
label {
    display: block;
    margin: 8px 0;
    cursor: pointer;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    background: #1d4ed8;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="container">

<h1>Security Awareness Quiz</h1>

<form action="submit_quiz.php" method="POST">

<!-- 🔥 PENTING: PASS EMAIL -->
<input type="hidden" name="email" value="<?= $_GET['email'] ?? '' ?>">

<?php if (empty($questions)): ?>
    <p>No questions available</p>
<?php else: ?>

<?php foreach ($questions as $i => $q): ?>
<div class="question">

<h3><?= ($i+1) ?>. <?= htmlspecialchars($q['question']) ?></h3>

<?php foreach ($q['options'] as $j => $opt): ?>
<label>
<input type="radio" name="q<?= $i ?>" value="<?= $j ?>" required>
<?= htmlspecialchars($opt) ?>
</label>
<?php endforeach; ?>

</div>
<?php endforeach; ?>

<?php endif; ?>

<button type="submit">Submit Quiz</button>

</form>

</div>

</body>
</html>