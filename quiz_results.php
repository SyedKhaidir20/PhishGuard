<?php
include 'config.php';

$email = $_GET['email'] ?? '';
$score = $_GET['score'] ?? 0;
$campaign_id = $_GET['campaign_id'] ?? 0;

$scoreClass = '';
if ($score >= 8) {
    $scoreClass = 'score-excellent';
} elseif ($score >= 5) {
    $scoreClass = 'score-good';
} else {
    $scoreClass = 'score-needs-improvement';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <link rel="stylesheet" href="styles.css"> <link rel="stylesheet" href="quiz_results_styles.css"> </head>
<body>
    <div class="container">
        <h1>Quiz Results</h1>
        <div class="result-card">
            <h2 class="score-display">Your Score: <?= $score ?>/10</h2>
            <p class="<?= $scoreClass ?>">
                <?php
                if ($score >= 8) {
                    echo 'Excellent! You have good security awareness.';
                } elseif ($score >= 5) {
                    echo 'Good, but there is room for improvement.';
                } else {
                    echo 'Please review security best practices.';
                }
                ?>
            </p>

            <h3>Security Tips:</h3>
            <ul>
                <li>Always verify the sender's email address</li>
                <li>Never click on suspicious links in emails</li>
                <li>Don't provide personal information in response to emails</li>
                <li>When in doubt, contact your IT department</li>
            </ul>
        </div>
    </div>
</body>
</html>