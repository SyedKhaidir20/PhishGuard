<?php
include 'config.php';

$questions = [
    [
        'question' => 'What is phishing?',
        'options' => [
            'A fishing sport',
            'A fraudulent attempt to obtain sensitive information',
            'A type of computer virus',
            'A social media platform'
        ],
        'answer' => 2
    ],
    [
        'question' => 'Which of these is a common sign of a phishing email?',
        'options' => [
            'Urgent language demanding immediate action',
            'Requests for personal information',
            'Suspicious sender email address',
            'All of the above'
        ],
        'answer' => 3
    ],
    [
        'question' => 'What should you do if you receive a suspicious email asking for your password?',
        'options' => [
            'Reply with your password immediately',
            'Forward it to your IT security team',
            'Click on any links to verify the request',
            'Ignore it but don\'t report it'
        ],
        'answer' => 2
    ],
    [
        'question' => 'Which of these is the safest way to access a company portal?',
        'options' => [
            'Clicking a link in an email',
            'Using a bookmark you created',
            'Searching for it on Google',
            'Using a link from a text message'
        ],
        'answer' => 3
    ],
    [
        'question' => 'What does "HTTPS" in a website URL indicate?',
        'options' => [
            'The website is definitely safe',
            'The connection is encrypted',
            'The website belongs to a bank',
            'The website has multimedia content'
        ],
        'answer' => 2
    ],
    [
        'question' => 'What should you do if you accidentally enter your credentials on a suspicious website?',
        'options' => [
            'Do nothing and hope for the best',
            'Immediately change your password',
            'Forward the email to coworkers as a warning',
            'Create a new email account'
        ],
        'answer' => 2
    ],
    [
        'question' => 'Which of these is NOT a good security practice?',
        'options' => [
            'Using the same password for multiple accounts',
            'Enabling two-factor authentication',
            'Regularly updating your passwords',
            'Being suspicious of unsolicited attachments'
        ],
        'answer' => 1
    ],
    [
        'question' => 'What is "spear phishing"?',
        'options' => [
            'Phishing that targets specific individuals',
            'Phishing using phone calls',
            'Phishing through social media',
            'Phishing that uses encrypted emails'
        ],
        'answer' => 4
    ],
    [
        'question' => 'Which of these is a red flag in an email?',
        'options' => [
            'Poor grammar and spelling mistakes',
            'Generic greetings like "Dear Customer"',
            'Requests for sensitive information',
            'All of the above'
        ],
        'answer' => 4
    ],
    [
        'question' => 'What should you do before opening an email attachment?',
        'options' => [
            'Verify the sender is who they claim to be',
            'Scan it with antivirus software',
            'Check if you were expecting the attachment',
            'All of the above'
        ],
        'answer' => 2
    ]
];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $campaign_id = isset($_GET['campaign_id']) ? $_GET['campaign_id'] : 0;

    foreach($questions as $i => $question) {
        if(isset($_POST['q'.$i]) && $_POST['q'.$i] == $question['answer']) {
            $score++;
        }
    }

    $email = $_POST['email'];
    $pdo->prepare("UPDATE simulation_results SET quiz_score = ? WHERE user_email = ? AND campaign_id = ?")->execute([$score, $email, $campaign_id]);
    
    header("Location: quiz_results.php?email=" . urlencode($email) . "&score=$score" . "&campaign_id=$campaign_id");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Security Awareness Quiz</title>
    <link rel="stylesheet" href="styles.css"> <link rel="stylesheet" href="quiz_styles.css"> </head>
<body>
    <div class="container">
        <h1>Security Awareness Quiz</h1>
        <form method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

            <?php foreach($questions as $i => $question): ?>
            <div class="question">
                <h3><?= ($i+1) . '. ' . $question['question'] ?></h3>
                <?php foreach($question['options'] as $j => $option): ?>
                <div class="option">
                    <input type="radio" name="q<?= $i ?>" id="q<?= $i ?>_<?= $j ?>" value="<?= $j ?>" required>
                    <label for="q<?= $i ?>_<?= $j ?>"><?= $option ?></label>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

            <button type="submit">Submit Quiz</button>
        </form>
    </div>
</body>
</html>