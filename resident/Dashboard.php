<?php
// dashboard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'barangay468_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    $host = "127.0.0.1";
    $dbname = "barangay468_db";
    $username = "root";
    $password = "";
    //$error = 'Database connection failed: ' . $e->getMessage();
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}

// Get current page
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// ✅ Fetch live counts from database
function getCount($pdo, $table, $statusColumn = 'status', $statusValue = 'Pending') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$statusColumn} = ?");
    $stmt->execute([$statusValue]);
    return $stmt->fetchColumn();
}

$stats = [
    'pending_permit' => getCount($pdo, 'permit_requests'),
    'pending_cert' => getCount($pdo, 'certificate_requests'),
    'pending_clearance' => getCount($pdo, 'clearance_requests')
];

// ✅ Fetch announcements
try {
    $stmt = $pdo->query("SELECT title, date, time, content FROM announcements ORDER BY date DESC LIMIT 3");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $announcements = [];
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard - Barangay Management System</title>
    <link rel="stylesheet" href="../css/resident.css">
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <div class="logo-text">Barangay<br>Management</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="Dashboard.php" class="nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a href="RequestDocuments.php" class="nav-link">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <span>Request Documents</span>
                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a href="ManageRequests.php" class="nav-link">
                    <div class="nav-link-content">
                        <div class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <span>Manage Documents</span>
                    </div>
                </a>
            </li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php" class="nav-link" onclick="showLogoutModal(event)">
                <div class="nav-link-content">
                    <div class="nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <span>Log out</span>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <div class="header-right">
                <div class="search-container">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                </div>
            </div>
        </header>

        <!-- ✅ Dashboard Section -->
        <div class="dashboard-grid">
            <div class="stats-section">
                <h2 class="today-data-title">Today's Data</h2>
                <div class="stats-grid">
                    <div class="stat-card-new">
                        <div class="stat-title-new">Pending Permit Requests</div>
                        <div class="stat-number-new"><?php echo $stats['pending_permit']; ?></div>
                    </div>
                    <div class="stat-card-new">
                        <div class="stat-title-new">Pending Certificate Requests</div>
                        <div class="stat-number-new"><?php echo $stats['pending_cert']; ?></div>
                    </div>
                    <div class="stat-card-new">
                        <div class="stat-title-new">Pending Clearance Requests</div>
                        <div class="stat-number-new"><?php echo $stats['pending_clearance']; ?></div>
                    </div>
                </div>
            </div>

            <!-- ✅ Announcements (Moved to Bottom) -->
            <div class="announcements-section" style="margin-top: 40px;">
                <h2 class="section-title-new">Announcements</h2>
                <?php if (count($announcements) > 0): ?>
                    <?php foreach ($announcements as $a): ?>
                        <div class="announcement-card">
                            <div class="announcement-header">
                                <div class="announcement-title"><?= htmlspecialchars($a['title']); ?></div>
                            </div>
                            <div class="announcement-date">
                                <?= htmlspecialchars($a['date']); ?> • <?= htmlspecialchars($a['time']); ?>
                            </div>
                            <div class="announcement-content">
                                <?= htmlspecialchars($a['content']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">No announcements available.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Logout Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to log out?</p>
            <div class="logout-modal-buttons">
                <button class="logout-btn confirm" onclick="confirmLogout()">Yes, Logout</button>
                <button class="logout-btn cancel" onclick="closeLogoutModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script src="../js/resident.js"></script>
</body>
</html>
