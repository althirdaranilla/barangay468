<?php
session_start();
require "../database/connection.php";
require "../database/log_activity.php";
require "./components/getIcon.php";
// Check if admin is logged in
if (!$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
log_activity($_SESSION['user_position'], "Viewed", "Manage Residents", $conn);

// Fetch officials data from database
$residents = [];
$sql_residents = "SELECT * FROM residents ORDER BY resident_id";
$result_residents = $conn->query($sql_residents);
if ($result_residents === false) {
    die("Error retrieving announcements: " . $conn->error);
}
while ($row = $result_residents->fetch_assoc()) {
    $residents[] = $row;
}
// Navigation menu items (✅ fixed URLs)
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
        'active' => true,
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
        'url' => 'Documents.php',
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
        'url' => '#',
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
        'url' => '#',
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
        'edit' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>',
        'delete' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>'
    ];
    
    return isset($icons[$icon_name]) ? $icons[$icon_name] : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Officials - Barangay Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            overflow-y: auto;
            height: 100vh;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo-text {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
            font-size: 14px;
            cursor: pointer;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #fff;
            color: #fff;
        }

        .nav-link.expandable {
            justify-content: space-between;
        }

        .nav-link-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            color: rgba(255,255,255,0.9);
            flex-shrink: 0;
        }

        .nav-icon svg {
            width: 100%;
            height: 100%;
        }

        .dropdown-icon {
            width: 16px;
            height: 16px;
            color: rgba(255,255,255,0.7);
            transition: transform 0.3s ease;
        }

        .nav-link.expanded .dropdown-icon {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            background: rgba(0,0,0,0.1);
            transition: max-height 0.3s ease;
        }

        .submenu.expanded {
            max-height: 1000px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px 12px 60px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .submenu-item:hover, .submenu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: rgba(255,255,255,0.5);
            color: #fff;
        }

        .submenu-icon {
            width: 12px;
            height: 12px;
            margin-right: 12px;
            color: rgba(255,255,255,0.6);
        }

        .logout-section {
            margin-top: 30px;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        .header {
            background: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .welcome-text {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            width: 250px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #999;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a90e2, #357abd);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .dashboard-section {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .btn-add {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background-color: #ffebee;
            color: #c62828;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
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

        .btn-edit {
            color: #3498db;
        }

        .btn-edit:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .btn-delete {
            color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .action-icon {
            width: 16px;
            height: 16px;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .entries-info {
            color: #6c757d;
        }

        .pagination {
            margin: 0;
        }

        .page-link {
            color: #3498db;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
        }

        .page-item.active .page-link {
            background-color: #3498db;
            border-color: #3498db;
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .mobile-menu {
                display: block;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }

            .search-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon"></div>
            <div class="logo-text">
                <div>Barangay 748</div>
                <div style="font-size: 11px; font-weight: 400; opacity: 0.8;">District IV, Manila</div>
            </div>
        </div>

        <ul class="nav-menu">
            <?php foreach($nav_items as $index => $item): ?>
            <li class="nav-item">
                <?php if($item['expandable'] && !empty($item['submenu'])): ?>
                    <div class="nav-link <?php echo $item['active'] ? 'active' : ''; ?> expandable" 
                         onclick="toggleDropdown(<?php echo $index; ?>)">
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
                        <a href="<?php echo $subitem['url']; ?>" class="submenu-item <?php echo basename($_SERVER['PHP_SELF']) == $subitem['url'] ? 'active' : ''; ?>">
                            <div class="submenu-icon">
                                <?php echo getIcon($subitem['icon']); ?>
                            </div>
                            <?php echo $subitem['name']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $item['url']; ?>" 
                       class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == $item['url'] ? 'active' : ''; ?>">
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
            <a href="AdminLogout.php" class="nav-link">
                <div class="nav-link-content">
                    <div class="nav-icon">
                        <?php echo getIcon('logout'); ?>
                    </div>
                    Log out
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome-text">Manage Barangay Officials</div>
            <div class="header-right">
                <div class="search-container">
                    <div class="search-icon">
                        <?php echo getIcon('search'); ?>
                    </div>
                    <input type="text" class="search-input" placeholder="Search officials..." id="searchInput">
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($admin_name, 0, 1)); ?>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <!-- Display messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <!-- Display messages 
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            -->
            <div class="section-header">
                <div class="section-title">Barangay Officials</div>
                <button class="btn-add" onclick="location.href='AddOfficial.php'">
                    <i class="fas fa-plus"></i> Add New Official
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Official ID</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($officials) > 0): ?>
                            <?php foreach($officials as $official): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($official['first_name'] . " " . $official['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($official['email']); ?></td>
                                <td><?php echo htmlspecialchars($official['position']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($official['status']) == 'active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo htmlspecialchars($official['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-edit" onclick="location.href='EditOfficial.php?id=<?php echo $official['id']; ?>'">
                                            <div class="action-icon">
                                                <?php echo getIcon('edit'); ?>
                                            </div>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="confirmDelete(<?php echo $official['id']; ?>, '<?php echo htmlspecialchars($official['first_name'] . " " . $official['first_name']); ?>')">
                                            <div class="action-icon">
                                                <?php echo getIcon('delete'); ?>
                                            </div>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No officials found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="entries-info">
                    Showing <?php echo count($officials); ?> entries
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Dropdown functionality
        function toggleDropdown(index) {
            const navLink = document.querySelector(`#sidebar .nav-item:nth-child(${index + 1}) .nav-link`);
            const submenu = document.getElementById(`submenu-${index}`);
            
            if (navLink && submenu) {
                navLink.classList.toggle('expanded');
                submenu.classList.toggle('expanded');
            }
        }

        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                // Skip the "No officials found" row
                if (row.cells.length === 1) return;
                
                const name = row.cells[0].textContent.toLowerCase();
                const id = row.cells[1].textContent.toLowerCase();
                const position = row.cells[2].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || id.includes(searchTerm) || position.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Delete confirmation
        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete ${name}?`)) {
                window.location.href = `ManageOfficials.php?delete_id=${id}`;
            }
        }

        // Initialize dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Expand the current active menu
            const activeSubmenuItem = document.querySelector('.submenu-item.active');
            if (activeSubmenuItem) {
                const activeSubmenu = activeSubmenuItem.closest('.submenu');
                if (activeSubmenu) {
                    activeSubmenu.classList.add('expanded');
                    const navLink = activeSubmenu.previousElementSibling;
                    if (navLink) {
                        navLink.classList.add('expanded');
                    }
                }
            }
        });

        // Debug session data
        console.log('Admin name:', '<?php echo $admin_name; ?>');
        
        // Add some error handling for missing elements
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
        });
    </script>
</body>
</html>