<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php'); 
    exit;
}

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'barangay468_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch residents data
$sql = "SELECT * FROM residents ORDER BY fullname ASC";
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
                'url' => 'BlotterRecords.php',
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
function getIcon($icon_name) {
    $icons = [
        'dashboard' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>',
        'documents' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>',
        'residents' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',
        'households' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>',
        'reports' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>',
        'logs' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/></svg>',
        'announcements' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 9h-2V9h2v2zm0-4h-2V5h2v2z"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>',
        'logout' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>',
        'circle' => '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="3"/></svg>',
        'chevron-down' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>',
        'chevron-up' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>',
        'eye' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>',
        'pencil' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>'
    ];
    
    return isset($icons[$icon_name]) ? $icons[$icon_name] : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resident Records</title>
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
            <div class="logo-icon"></div>
            <div class="logo-text">
                <div>Barangay 468</div>
                <div style="font-size: 11px; font-weight: 400; opacity: 0.8;">District IV, Manila</div>
            </div>
        </div>

        <ul class="nav-menu">
            <?php foreach($nav_items as $index => $item): ?>
            <li class="nav-item">
                <?php if($item['expandable'] && !empty($item['submenu'])): ?>
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
                    <div class="submenu <?php echo isset($item['expanded']) && $item['expanded'] ? 'expanded' : ''; ?>" id="submenu-<?php echo $index; ?>">
                        <?php foreach($item['submenu'] as $subitem): ?>
                        <a href="<?php echo $subitem['url']; ?>" class="submenu-item <?php echo isset($subitem['active']) && $subitem['active'] ? 'active' : ''; ?>">
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
                <button class="add-resident-btn" onclick="window.location.href='/add_resident.php'">+ Add Resident</button>
            </div>
            <table class="residents-table" id="residentsTable">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Resident Id</th>
                        <th>Age</th>
                        <th>Civil Status</th>
                        <th>Gender</th>
                        <th>Contact Number</th>
                        <th>Email Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php 
                        $entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 5;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start = ($page - 1) * $entries_per_page;
                        $total_rows = mysqli_num_rows($result);
                        mysqli_data_seek($result, $start);
                        for ($i = 0; $i < $entries_per_page && $row = mysqli_fetch_assoc($result); $i++): 
                        ?>
                            <tr>
                                <td class="resident-name">
                                    <div class="resident-avatar">
                                        <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($row['fullname']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['resident_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['civil_status']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view" title="View" onclick="window.location.href='/view_resident.php?id=<?php echo $row['resident_id']; ?>'">
                                            <div class="action-icon">
                                                <?php echo getIcon('eye'); ?>
                                            </div>
                                        </button>
                                        <button class="action-btn edit" title="Edit" onclick="window.location.href='/edit_resident.php?id=<?php echo $row['resident_id']; ?>'">
                                            <div class="action-icon">
                                                <?php echo getIcon('pencil'); ?>
                                            </div>
                                        </button>
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