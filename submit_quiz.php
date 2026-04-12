<?php
include 'config.php';

$score = 0;
$total = 0;

/* ===== KIRA SCORE ===== */
$stmt = $pdo->query("SELECT * FROM questions");

$i = 0;

while ($row = $stmt->fetch()) {

    $opt = $pdo->prepare("SELECT * FROM options WHERE question_id=?");
    $opt->execute([$row['id']]);

    $correct_index = null;
    $index = 0;

    while ($o = $opt->fetch()) {
        if ($o['is_correct'] == 1) {
            $correct_index = $index;
        }
        $index++;
    }

    if (isset($_POST["q$i"])) {
        if ($_POST["q$i"] == $correct_index) {
            $score++;
        }
    }

    $total++;
    $i++;
}

/* ===== KIRA PERCENT & RISK ===== */
$percentage = ($total > 0) ? ($score / $total) * 100 : 0;
$risk = ($percentage >= 70) ? 'low' : 'high';

/* ===== AMBIL EMAIL ===== */
$email = $_POST['email'] ?? '';

/* ===== UPDATE DATABASE ===== */
if (!empty($email)) {

    $stmt = $pdo->prepare("
        UPDATE simulation_results 
        SET quiz_score = ?, risk_level = ?, data_submitted = 1
        WHERE user_email = ?
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->execute([$score, $risk, $email]);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Quiz Result</title>

<style>
body {
    font-family: 'Segoe UI';
    background: #f1f5f9;
    text-align: center;
    padding-top: 100px;
}

.card {
    background: white;
    display: inline-block;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.score {
    font-size: 28px;
    margin: 15px 0;
}

.low {
    color: green;
    font-weight: bold;
}

.high {
    color: red;
    font-weight: bold;
}

a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background: #1d4ed8;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
</style>
</head>

<body>

<div class="card">

<h1>Quiz Result</h1>

<div class="score">
    <?= $score ?> / <?= $total ?>
</div>

<?php if ($risk == 'low'): ?>
    <p class="low">Low Risk</p>
<?php else: ?>
    <p class="high">High Risk</p>
<?php endif; ?>

<a href="phishing_quiz.php">Try Again</a>

</div>

</body>
</html>