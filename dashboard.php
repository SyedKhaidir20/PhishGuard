<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$campaigns = [];
$results = [];

$result1 = $mysqli->query("SELECT * FROM campaigns ORDER BY created_at DESC");
if ($result1) {
    $campaigns = $result1->fetch_all(MYSQLI_ASSOC);
}

$result2 = $mysqli->query("SELECT * FROM simulation_results ORDER BY created_at DESC");
if ($result2) {
    $results = $result2->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['clear_data'])) {
    try {
        $mysqli->begin_transaction();


        $mysqli->query("DELETE FROM campaigns");


        $mysqli->query("ALTER TABLE simulation_results AUTO_INCREMENT = 1");
        $mysqli->query("ALTER TABLE campaign_targets AUTO_INCREMENT = 1");
        $mysqli->query("ALTER TABLE campaign_files AUTO_INCREMENT = 1");
        $mysqli->query("ALTER TABLE campaigns AUTO_INCREMENT = 1");

        $mysqli->commit();
        $_SESSION['success_message'] = "All data cleared successfully!";
    } catch (mysqli_sql_exception $e) {
        if ($mysqli->errno) {
            $mysqli->rollback();
        }
        $_SESSION['error_message'] = "Error clearing data: " . $e->getMessage();
    }

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Phishing Simulator</title>
    <link rel="stylesheet" href="styles.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
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
    <div class="nav-bar">
        <button onclick="window.location.href='admin_panel.php'"><i class="fas fa-user-cog"></i> Admin Panel</button>
        <button class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</button>
        <button onclick="window.location.href='add_question.php'"><i class="fas fa-question-circle"></i> Add Question</button>
        <button onclick="window.location.href='manage_questions.php'"><i class="fas fa-list"></i> Manage Questions </button>
        <button onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="container">
        <h2><i class="fas fa-chart-line"></i> Simulation Dashboard</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><i class="fas fa-project-diagram"></i> <?= count($campaigns) ?></div>
                <div class="stat-label">Active Campaigns</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><i class="fas fa-users"></i> <?= count($results) ?></div>
                <div class="stat-label">Total Users Tracked</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <i class="fas fa-mouse-pointer"></i> <?= count($results) > 0 ? round(array_sum(array_column($results, 'link_clicked')) / count($results) * 100) : 0 ?>%
                </div>
                <div class="stat-label">Click Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><i class="fas fa-user-shield"></i> <?= array_sum(array_column($results, 'data_submitted')) ?></div>
                <div class="stat-label">Users Phished</div>
            </div>
        </div>

        <div>

        </div>

        <h3><i class="fas fa-table"></i> Campaign Results</h3>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success"><?= $_SESSION['success_message'] ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?= $_SESSION['error_message'] ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="POST" id="clearDataForm">
            <input type="hidden" name="clear_data" value="1">
            <div class="button-group">
                <button type="button" class="btn danger" id="clearAllDataBtn">
                    <i class="fas fa-trash-alt"></i> Clear All Data
                </button>
                <button type="button" class="btn" onclick="exportResults()">
                    <i class="fas fa-download"></i> Export Results
                </button>
                <button type="button" class="btn" onclick="refreshStats()">
                    <i class="fas fa-sync-alt"></i> Refresh Stats
                </button>
            </div>
        </form>

        <table id="resultsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>User Email <i class="fas fa-sort"></i></th>
                    <th>Campaign <i class="fas fa-sort"></i></th>
                    <th>Email Opened <i class="fas fa-sort"></i></th>
                    <th>File Opened <i class="fas fa-sort"></i></th>
                    <th>Link Clicked <i class="fas fa-sort"></i></th>
                    <th>Data Submitted <i class="fas fa-sort"></i></th>
                    <th>Quiz Score <i class="fas fa-sort"></i></th>
                    <th>Risk Level <i class="fas fa-sort"></i></th>
                    <th>Date <i class="fas fa-sort"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result):
                    $campaignName = 'N/A';
                    if (!empty($result['campaign_id'])) {
                        $stmt = $pdo->prepare("SELECT name FROM campaigns WHERE id = ?");
                        $stmt->execute([$result['campaign_id']]);
                        $campaign = $stmt->fetch();
                        if ($campaign) {
                            $campaignName = $campaign['name'];
                        }
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($result['user_email']) ?></td>
                    <td><?= htmlspecialchars($campaignName) ?></td>
                    <td><?= $result['email_opened'] ? '<span class="icon-check"><i class="fas fa-check-circle"></i> Yes</span>' : '<span class="icon-cross"><i class="fas fa-times-circle"></i> No</span>' ?></td>
                    <td><?= $result['file_opened'] ? '<span class="icon-check"><i class="fas fa-check-circle"></i> Yes</span>' : '<span class="icon-cross"><i class="fas fa-times-circle"></i> No</span>' ?></td>
                    <td><?= $result['link_clicked'] ? '<span class="icon-check"><i class="fas fa-check-circle"></i> Yes</span>' : '<span class="icon-cross"><i class="fas fa-times-circle"></i> No</span>' ?></td>
                    <td><?= $result['data_submitted'] ? '<span class="icon-check"><i class="fas fa-check-circle"></i> Yes</span>' : '<span class="icon-cross"><i class="fas fa-times-circle"></i> No</span>' ?></td>
                    <td><?= $result['quiz_score'] !== null ? htmlspecialchars($result['quiz_score']) . '/10' : 'N/A' ?></td>
                    <td>
                        <?php
                            if ($result['risk_level'] == 'high') echo '<span style="color: #dc3545; font-weight: bold;"><i class="fas fa-exclamation-triangle"></i> High</span>';
                            elseif ($result['risk_level'] == 'medium') echo '<span style="color: #ffc107; font-weight: bold;"><i class="fas fa-minus-circle"></i> Medium</span>';
                            else echo '<span style="color: #28a745; font-weight: bold;"><i class="fas fa-check-circle"></i> Low</span>';
                        ?>
                    </td>
                    <td><?= date('Y-m-d H:i:s', strtotime($result['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#resultsTable', {
                columnDefs: [
                    { targets: '_all', orderable: true }
                ],
                language: {
                    search: "Filter records:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            document.getElementById('clearAllDataBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! All simulation data will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, clear it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('clearDataForm').submit();
                    }
                });
            });

            window.confirmLogout = function() {
                Swal.fire({
                    title: 'Are you sure you want to logout?',
                    text: "You will need to log back in to access the dashboard.",
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

            window.exportResults = function() {
                window.location.href = 'export_results.php';
            };

            window.refreshStats = function() {
                window.location.reload();
            };

            <?php if (isset($_SESSION['success_message'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= htmlspecialchars($_SESSION['success_message']) ?>',
                    showConfirmButton: false,
                    timer: 3000
                });
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= htmlspecialchars($_SESSION['error_message']) ?>',
                    confirmButtonColor: '#dc3545'
                });
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });
    </script>



</body>
</html>