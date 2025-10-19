<?php
session_start();
require "./components/getIcon.php";
require "../database/connection.php";
if (!$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

// Database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch residents data
$sql = "SELECT * FROM admin_users ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// Navigation menu items with dropdown structure
$nav_items = [
    [
        'name' => 'Dashboard',
        'icon' => 'dashboard',
        'url' => 'Dashboard.php',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ],
    [
        'name' => 'Brgy. Officials and Staffs',
        'icon' => 'users',
        'url' => '#',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Officials',
                'url' => 'ManageOfficials.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Manage Staffs',
                'url' => 'ManageStaffs.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Documents',
        'icon' => 'documents',
        'url' => 'documents.php',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Clearance Request',
                'url' => 'ClearanceRequest.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Manage Permit Request',
                'url' => 'PermitRequest.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Manage Certificate Request',
                'url' => 'CertificateRequest.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Residents',
        'icon' => 'residents',
        'url' => '#',
        'active' => true,
        'expanded' => true,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Resident Records',
                'url' => 'Residents.php',
                'icon' => 'circle',
                'active' => true
            ],
            [
                'name' => 'User Approval',
                'url' => 'UserApproval.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Households',
        'icon' => 'households',
        'url' => 'households.php',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ],
    [
        'name' => 'Reports',
        'icon' => 'reports',
        'url' => 'reports.php',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Blotter Records',
                'url' => 'Blotter.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Logs',
        'icon' => 'logs',
        'url' => 'logs.php',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Audit Logs',
                'url' => 'AuditLogs.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Transaction Logs',
                'url' => 'TransactionLogs.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Announcements',
        'icon' => 'announcements',
        'url' => 'Announcement.php',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ]
];

// Function to get icon SVG

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resident Records</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .admin-btn-action {
            background: none;
            border: none;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .admin-action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
        </svg>
    </button>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to log out? You will be redirected to the login page.</p>
            <div class="logout-modal-buttons">
                <button class="logout-btn confirm" onclick="confirmLogout()">Yes, Log Out</button>
                <button class="logout-btn cancel" onclick="closeLogoutModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon"></div>
            <div class="logo-text">
                <div>Barangay 468</div>
                <div style="font-size: 11px; font-weight: 400; opacity: 0.8;">District IV, Manila</div>
            </div>
        </div>

        <ul class="nav-menu">
            <?php foreach ($nav_items as $index => $item): ?>
                <li class="nav-item">
                    <?php if ($item['expandable'] && !empty($item['submenu'])): ?>
                        <div class="nav-link <?php echo $item['active'] ? 'active' : ''; ?> expandable <?php echo isset($item['expanded']) && $item['expanded'] ? 'expanded' : ''; ?>"
                            onclick="toggleDropdown(<?php echo $index; ?>, event)">
                            <div class="nav-link-content">
                                <div class="nav-icon">
                                    <?php echo getIcon($item['icon']); ?>
                                </div>
                                <?php echo $item['name']; ?>
                            </div>
                            <div class="dropdown-icon">
                                <?php echo getIcon('chevron-down'); ?>
                            </div>
                        </div>
                        <div class="submenu <?php echo isset($item['expanded']) && $item['expanded'] ? 'expanded' : ''; ?>"
                            id="submenu-<?php echo $index; ?>">
                            <?php foreach ($item['submenu'] as $subitem): ?>
                                <a href="<?php echo $subitem['url']; ?>"
                                    class="submenu-item <?php echo isset($subitem['active']) && $subitem['active'] ? 'active' : ''; ?>">
                                    <div class="submenu-icon">
                                        <?php echo getIcon($subitem['icon']); ?>
                                    </div>
                                    <?php echo $subitem['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo $item['active'] ? 'active' : ''; ?>">
                            <div class="nav-link-content">
                                <div class="nav-icon">
                                    <?php echo getIcon($item['icon']); ?>
                                </div>
                                <?php echo $item['name']; ?>
                            </div>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="logout-section">
            <div class="nav-link" onclick="showLogoutModal()" style="cursor: pointer;">
                <div class="nav-link-content">
                    <div class="nav-icon">
                        <?php echo getIcon('logout'); ?>
                    </div>
                    Log out
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
            <div class="header-right">
                <div class="search-container">
                    <div class="search-icon">
                        <?php echo getIcon('search'); ?>
                    </div>
                    <input type="text" class="search-input" placeholder="Search" id="searchInput">
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                </div>
            </div>
        </div>

        <div class="residents-section">
            <div class="section-title">List of Residents</div>
            <div class="table-controls">
                <div>
                    Show
                    <select class="entries-select" onchange="updateEntries(this.value)">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    entries
                </div>
                <button class="add-resident-btn" onclick="window.location.href='./AddResidents.php'">+ Add
                    Resident</button>
            </div>
            <table class="residents-table" id="residentsTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php
                        $entries_per_page = isset($_GET['entries']) ? (int) $_GET['entries'] : 5;
                        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                        $start = ($page - 1) * $entries_per_page;
                        $total_rows = mysqli_num_rows($result);
                        mysqli_data_seek($result, $start);
                        for ($i = 0; $i < $entries_per_page && $row = mysqli_fetch_assoc($result); $i++):
                            ?>
                            <tr>
                                <td class="resident-name">
                                    <div class="resident-avatar">
                                        <?php echo strtoupper(substr($row['first_name'], 0, 1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if($row['status'] == "inactive"): ?>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                            <div class="admin-action-buttons">
                                                <button class="admin-btn-action admin-btn-edit" title="Approve" type="submit" name="approve">
                                                    <svg class="admin-action-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M5 11.917 9.724 16.5 19 7.5"/>
                                                    </svg>
                                                </button>
                                                <button class="admin-btn-action admin-btn-edit" title="Reject" type="submit" name="reject">
                                                    <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>
                                        
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No residents found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (mysqli_num_rows($result) > $entries_per_page): ?>
                <div class="pagination">
                    <?php
                    $total_pages = ceil($total_rows / $entries_per_page);
                    for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&entries=<?php echo $entries_per_page; ?>" <?php echo $i == $page ? 'style="font-weight: bold; color: #4CAF50;"' : ''; ?>>
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../js/admin.js"></script>
</body>

</html>
<?php
mysqli_close($conn);
?>