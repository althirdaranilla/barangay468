<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    error_log('Redirecting: admin_logged_in not set');
} elseif ($_SESSION['admin_logged_in'] !== true) {
    error_log('Redirecting: admin_logged_in not true');
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
        'chevron-up' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>'
    ];
    
    return isset($icons[$icon_name]) ? $icons[$icon_name] : '';
}

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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: relative;
            overflow-y: auto;
            max-height: 100vh;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 1px, transparent 1px),
                        radial-gradient(circle at 80% 40%, rgba(255,255,255,0.08) 1px, transparent 1px),
                        radial-gradient(circle at 40% 80%, rgba(255,255,255,0.06) 1px, transparent 1px);
            pointer-events: none;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
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
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            background: linear-gradient(45deg, #e74c3c 0%, #e74c3c 50%, #3498db 50%, #3498db 100%);
            border-radius: 50%;
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
            position: relative;
            z-index: 1;
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

        .dropdown-icon svg {
            width: 100%;
            height: 100%;
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
            max-height: 300px;
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

        .submenu-item:hover {
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

        .submenu-icon svg {
            width: 100%;
            height: 100%;
        }

        .logout-section {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            z-index: 1;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }

        .header {
            background: rgba(255,255,255,0.95);
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
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }

        .residents-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .families-icon {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .households-icon {
            background: linear-gradient(135deg, #f39c12, #d68910);
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .stat-title {
            font-size: 16px;
            font-weight: 500;
            color: #666;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #333;
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

        /* Logout confirmation modal styles */
        .logout-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .logout-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .logout-modal h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .logout-modal p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .logout-modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .logout-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn.confirm {
            background: #e74c3c;
            color: white;
        }

        .logout-btn.confirm:hover {
            background: #c0392b;
        }

        .logout-btn.cancel {
            background: #95a5a6;
            color: white;
        }

        .logout-btn.cancel:hover {
            background: #7f8c8d;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 999;
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
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }

            .search-input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .logout-modal-content {
                margin: 30% auto;
                width: 95%;
            }

            .logout-modal-buttons {
                flex-direction: column;
            }
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .submenu.expanded {
            max-height: 1000px; /* Adjust based on your content */
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

    <script>
    // Dropdown functionality
    function toggleDropdown(index, event) {
        // Prevent default only for the parent link (href="#")
        if (event.target.closest('.nav-link.expandable') && !event.target.closest('.submenu-item')) {
            event.preventDefault(); // Prevent default only for the parent link
            const navLink = document.querySelector(`#sidebar .nav-item:nth-child(${index + 1}) .nav-link`);
            const submenu = document.getElementById(`submenu-${index}`);
            
            if (navLink && submenu) {
                navLink.classList.toggle('expanded');
                submenu.classList.toggle('expanded');
                
                // Close other open dropdowns
                document.querySelectorAll('.submenu').forEach((menu) => {
                    if (menu.id !== `submenu-${index}` && menu.classList.contains('expanded')) {
                        menu.classList.remove('expanded');
                        const parentLink = menu.previousElementSibling;
                        if (parentLink && parentLink.classList.contains('nav-link')) {
                            parentLink.classList.remove('expanded');
                        }
                    }
                });
            }
        }
    }

    // Sidebar toggle for mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    // Logout functionality
    function showLogoutModal() {
        document.getElementById('logoutModal').style.display = 'block';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = '../logout.php';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target == modal) {
            closeLogoutModal();
        }
    };

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !mobileMenu.contains(event.target) &&
            !event.target.classList.contains('mobile-menu')) {
            sidebar.classList.remove('active');
        }
    });

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        console.log('Searching for:', searchTerm);
    });

    // Initialize dropdowns based on PHP data
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-expand items that are marked as expanded in PHP
        const expandedItems = document.querySelectorAll('.nav-link.expanded');
        expandedItems.forEach(item => {
            const submenu = item.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                submenu.classList.add('expanded');
            }
        });

        // Ensure submenu item clicks navigate to the correct URL
        document.querySelectorAll('.submenu-item').forEach(submenuItem => {
            submenuItem.addEventListener('click', function(event) {
                event.stopPropagation(); // Stop bubbling to prevent toggleDropdown
                window.location.href = this.getAttribute('href'); // Explicitly navigate
            });
        });
    });
    </script>
</body>
</html>