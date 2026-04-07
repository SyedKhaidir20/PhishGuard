<?php
include 'config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="phishing_simulation_results_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');


fputcsv($output, [
    'User Email', 
    'Campaign', 
    'Email Opened', 
    'File Opened', 
    'Link Clicked', 
    'Data Submitted', 
    'Quiz Score', 
    'Risk Level', 
    'Date'
]);


$results = $pdo->query("
    SELECT r.user_email, c.name as campaign_name, r.email_opened, r.file_opened, 
           r.link_clicked, r.data_submitted, r.quiz_score, r.risk_level, r.created_at
    FROM simulation_results r
    JOIN campaigns c ON r.campaign_id = c.id
    ORDER BY r.created_at DESC
")->fetchAll();


foreach($results as $row) {
    fputcsv($output, [
        $row['user_email'],
        $row['campaign_name'],
        $row['email_opened'] ? 'Yes' : 'No',
        $row['file_opened'] ? 'Yes' : 'No',
        $row['link_clicked'] ? 'Yes' : 'No',
        $row['data_submitted'] ? 'Yes' : 'No',
        $row['quiz_score'] ? $row['quiz_score'].'/10' : 'N/A',
        ucfirst($row['risk_level']),
        $row['created_at']
    ]);
}

fclose($output);
exit();
?>