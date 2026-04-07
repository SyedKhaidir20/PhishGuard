<?php
include 'config.php';

if(isset($_GET['email']) && isset($_GET['campaign_id']) && isset($_GET['file'])) {

    $email = $_GET['email'];
    $campaign_id = $_GET['campaign_id'];
    $file_identifier = $_GET['file'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_numeric($campaign_id) || empty($file_identifier)) {
        header("Location: index.php");
        exit;
    }

    try {
        $stmt_update = $pdo->prepare("UPDATE simulation_results SET file_opened = 1 WHERE user_email = ? AND campaign_id = ?");
        $stmt_update->execute([$email, $campaign_id]);

        $stmt_select_file = $pdo->prepare("SELECT file_path, file_name FROM campaign_files WHERE campaign_id = ? AND file_path LIKE ?");
        $stmt_select_file->execute([$campaign_id, '%/' . $file_identifier]);

        $file_data = $stmt_select_file->fetch(PDO::FETCH_ASSOC);

        if($file_data && file_exists($file_data['file_path'])) {
            $file_actual_path = $file_data['file_path'];
            $original_file_name = $file_data['file_name'];

            $original_file_name = preg_replace('/[^a-zA-Z0-9\._-]/', '', $original_file_name);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $original_file_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_actual_path));

            if (ob_get_level()) {
                ob_end_clean();
            }

            readfile($file_actual_path);
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database error in track_file.php: " . $e->getMessage());
        header("Location: index.php");
        exit;
    }
}

header("Location: index.php");
exit;
?>