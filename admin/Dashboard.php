<?php
session_start();
require "./components/getIcon.php";

if (!isset($_SESSION['admin_logged_in'])) {
    
}

// Sample data - in a real application, this would come from a database
$dashboard_data = [
    'residents' => 200,
    'families' => 30,
    'households' => 80,
    'admin_name' => isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin',
    'current_date' => date('Y-m-d'),
    'current_time' => date('H:i:s')
];

// Navigation menu items with dropdown structure
$nav_items = [
    [
        'name' => 'Dashboard',
        'icon' => 'dashboard',
        'url' => 'Dashboard.php',
        'active' => true,
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
        'url' => 'Residents.php',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Resident Records',
                'url' => 'Residents.php',
                'icon' => 'circle'
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
        'url' => 'announcements.php',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ]
];

// Function to get icon SVG


// Function to format numbers
function formatNumber($number) {
    return number_format($number);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
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
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <div class="logo-text">
                <div>Barangay 468</div>
                <div style="font-size: 11px; font-weight: 400; opacity: 0.8;">District IV, Manila</div>
            </div>
        </div>

        <ul class="nav-menu">
            <?php foreach($nav_items as $index => $item): ?>
            <li class="nav-item">
                <?php if($item['expandable'] && !empty($item['submenu'])): ?>
                    <div class="nav-link <?php echo $item['active'] ? 'active' : ''; ?> expandable" 
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
                    <div class="submenu" id="submenu-<?php echo $index; ?>">
                        <?php foreach($item['submenu'] as $subitem): ?>
                        <a href="<?php echo $subitem['url']; ?>" class="submenu-item">
                            <div class="submenu-icon">
                                <?php echo getIcon($subitem['icon']); ?>
                            </div>
                            <?php echo $subitem['name']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $item['url']; ?>" 
                       class="nav-link <?php echo $item['active'] ? 'active' : ''; ?>">
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
            <div class="welcome-text">Welcome, <?php echo htmlspecialchars($dashboard_data['admin_name']); ?></div>
            <div class="header-right">
                <div class="search-container">
                    <div class="search-icon">
                        <?php echo getIcon('search'); ?>
                    </div>
                    <input type="text" class="search-input" placeholder="Search" id="searchInput">
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($dashboard_data['admin_name'], 0, 1)); ?>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="section-title">Today's data</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon residents-icon">
                            <?php echo getIcon('residents'); ?>
                        </div>
                        <div class="stat-title">Residents</div>
                    </div>
                    <div class="stat-number"><?php echo formatNumber($dashboard_data['residents']); ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon families-icon">
                            <?php echo getIcon('users'); ?>
                        </div>
                        <div class="stat-title">Families</div>
                    </div>
                    <div class="stat-number"><?php echo formatNumber($dashboard_data['families']); ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon households-icon">
                            <?php echo getIcon('households'); ?>
                        </div>
                        <div class="stat-title">Households</div>
                    </div>
                    <div class="stat-number"><?php echo formatNumber($dashboard_data['households']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/admin.js"></script>
</body>
</html>