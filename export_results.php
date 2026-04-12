<?php

require_once __DIR__ . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

include 'config.php';

$dompdf = new Dompdf();

// Ambil data
$results = $pdo->query("
    SELECT r.user_email, c.name as campaign_name, r.email_opened, r.file_opened, 
           r.link_clicked, r.data_submitted, r.quiz_score, r.risk_level, r.created_at
    FROM simulation_results r
    JOIN campaigns c ON r.campaign_id = c.id
    ORDER BY r.created_at DESC
")->fetchAll();

// HTML
$html = '
<h2 style="text-align:center;">Phishing Simulation Results</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
<thead>
<tr style="background:#f2f2f2;">
<th>User Email</th>
<th>Campaign</th>
<th>Email Opened</th>
<th>File Opened</th>
<th>Link Clicked</th>
<th>Data Submitted</th>
<th>Quiz Score</th>
<th>Risk Level</th>
<th>Date</th>
</tr>
</thead>
<tbody>
';

foreach($results as $row) {
    $html .= '<tr>
        <td>'.$row['user_email'].'</td>
        <td>'.$row['campaign_name'].'</td>
        <td>'.($row['email_opened'] ? 'Yes' : 'No').'</td>
        <td>'.($row['file_opened'] ? 'Yes' : 'No').'</td>
        <td>'.($row['link_clicked'] ? 'Yes' : 'No').'</td>
        <td>'.($row['data_submitted'] ? 'Yes' : 'No').'</td>
        <td>'.($row['quiz_score'] ? $row['quiz_score'].'/10' : 'N/A').'</td>
        <td>'.ucfirst($row['risk_level']).'</td>
        <td>'.$row['created_at'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Generate PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("phishing_results.pdf", ["Attachment" => true]);

exit;