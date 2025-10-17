<?php
require "../database/connection.php";
require "../database/log_activity.php";

// Simulating user authentication - in a real application, this would come from session
$isAdmin = true;
$admin_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_position'];

log_activity($user_role, "Viewed", "Audit Logs", $conn);

// Sample audit logs data
$auditLogs = [
    [
        'log_id' => 'Adm-1001',
        'user_role' => 'System Administrator',
        'action_made' => 'Added a resident',
        'time_stamp' => '03/06/2025 - 6:48:20 pm',
        'device' => 'Desktop'
    ],
    [
        'log_id' => 'Adm-1001',
        'user_role' => 'System Administrator',
        'action_made' => 'Deleted a resident data',
        'time_stamp' => '03/01/2025 - 6:48:20 pm',
        'device' => 'Desktop'
    ],
    [
        'log_id' => 'Adm-1001',
        'user_role' => 'System Administrator',
        'action_made' => 'Added a resident',
        'time_stamp' => '03/03/2025 - 6:48:20 pm',
        'device' => 'Desktop'
    ],
    [
        'log_id' => 'Adm-1001',
        'user_role' => 'System Administrator',
        'action_made' => 'Deleted a resident data',
        'time_stamp' => '03/02/2025 - 6:48:20 pm',
        'device' => 'Desktop'
    ],
    [
        'log_id' => 'Adm-1001',
        'user_role' => 'System Administrator',
        'action_made' => 'Added a resident',
        'time_stamp' => '03/04/2025 - 6:48:20 pm',
        'device' => 'Desktop'
    ],
    [
        'log_id' => 'Adm-1002',
        'user_role' => 'Staff',
        'action_made' => 'Updated household information',
        'time_stamp' => '03/05/2025 - 2:30:15 pm',
        'device' => 'Laptop'
    ],
    [
        'log_id' => 'Adm-1003',
        'user_role' => 'Admin',
        'action_made' => 'Processed clearance request',
        'time_stamp' => '03/04/2025 - 10:15:45 am',
        'device' => 'Tablet'
    ],
    [
        'log_id' => 'Adm-1004',
        'user_role' => 'System Administrator',
        'action_made' => 'Generated report',
        'time_stamp' => '03/03/2025 - 4:20:30 pm',
        'device' => 'Desktop'
    ]
];

// Navigation menu items
$nav_items = [
    [
        'name' => 'Dashboard',
        'icon' => 'dashboard',
        'url' => 'AdminDashboard.php',
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
                'url' => '../admin/ManageOfficials.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Manage Staffs',
                'url' => 'manage-staffs.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Documents',
        'icon' => 'documents',
        'url' => '#',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Clearance Request',
                'url' => 'Clearance.php',
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
                'url' => 'ResidentRecords.php',
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
        'url' => '#',
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
                'url' => 'BlotterRecords.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'Logs',
        'icon' => 'logs',
        'url' => '#',
        'active' => true,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Audit Logs',
                'url' => 'AuditLogs.php',
                'icon' => 'circle',
                'active' => true
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
        'url' => '#',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ]
];

// Action type colors
$actionColors = [
    'Added' => '#28a745',
    'Deleted' => '#dc3545',
    'Updated' => '#17a2b8',
    'Processed' => '#ffc107',
    'Generated' => '#6f42c1',
    'Viewed' => '#6c757d'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Audit Logs</title>
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

        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            overflow-y: auto;
            height: 100vh;
            z-index: 1000;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .admin-logo-icon {
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

        .admin-logo-text {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .admin-nav-menu {
            list-style: none;
        }

        .admin-nav-item {
            margin-bottom: 2px;
        }

        .admin-nav-link {
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

        .admin-nav-link:hover, .admin-nav-link.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #fff;
            color: #fff;
        }

        .admin-nav-link.expandable {
            justify-content: space-between;
        }

        .admin-nav-link-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .admin-nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            color: rgba(255,255,255,0.9);
            flex-shrink: 0;
        }

        .admin-nav-icon svg {
            width: 100%;
            height: 100%;
        }

        .admin-dropdown-icon {
            width: 16px;
            height: 16px;
            color: rgba(255,255,255,0.7);
            transition: transform 0.3s ease;
        }

        .admin-nav-link.expanded .admin-dropdown-icon {
            transform: rotate(180deg);
        }

        .admin-submenu {
            max-height: 0;
            overflow: hidden;
            background: rgba(0,0,0,0.1);
            transition: max-height 0.3s ease;
        }

        .admin-submenu.expanded {
            max-height: 1000px;
        }

        .admin-submenu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px 12px 60px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .admin-submenu-item:hover, .admin-submenu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: rgba(255,255,255,0.5);
            color: #fff;
        }

        .admin-submenu-icon {
            width: 12px;
            height: 12px;
            margin-right: 12px;
            color: rgba(255,255,255,0.6);
        }

        .admin-logout-section {
            margin-top: 30px;
        }

        .admin-main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        .admin-header {
            background: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .admin-welcome-text {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-search-container {
            position: relative;
        }

        .admin-search-input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            width: 250px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .admin-search-input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .admin-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #999;
        }

        .admin-filter-container {
            position: relative;
        }

        .admin-filter-btn {
            background: #fff;
            border: 2px solid #e0e0e0;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-filter-btn:hover {
            border-color: #4a90e2;
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

        .admin-dashboard-section {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .admin-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .admin-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .admin-table-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .admin-entries-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-entries-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .admin-export-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-export-btn:hover {
            background: #218838;
        }

        .admin-table-container {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .admin-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
        }

        .admin-table tr:hover {
            background-color: #f8f9fa;
        }

        .admin-action-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .admin-device-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background-color: #e9ecef;
            color: #495057;
            display: inline-block;
        }

        .admin-table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .admin-entries-info {
            color: #6c757d;
        }

        .admin-pagination {
            margin: 0;
        }

        .admin-page-link {
            color: #3498db;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            text-decoration: none;
            display: inline-block;
        }

        .admin-page-item.active .admin-page-link {
            background-color: #3498db;
            border-color: #3498db;
            color: white;
        }

        .admin-mobile-menu {
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

        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease;
            }

            .admin-sidebar.active {
                left: 0;
            }

            .admin-mobile-menu {
                display: block;
            }

            .admin-main-content {
                margin-left: 0;
                width: 100%;
            }

            .admin-header {
                flex-direction: column;
                gap: 15px;
            }

            .admin-search-input {
                width: 100%;
            }

            .admin-section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 5.5V7H9V5.5L3 7V9L9 10.5V12L3 13.5V15.5L9 14V16H15V14L21 15.5V13.5L15 12V10.5L21 9Z" />
                    </svg>
                </div>
                <div class="admin-logo-text">
                    Barangay System<br>Admin Panel
                </div>
            </div>

            <ul class="admin-nav-menu">
                <?php foreach ($nav_items as $item): ?>
                <li class="admin-nav-item">
                    <div class="admin-nav-link <?php echo $item['expandable'] ? 'expandable' : ''; ?> <?php echo $item['active'] ? 'active' : ''; ?>">
                        <div class="admin-nav-link-content">
                            <div class="admin-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                            <span><?php echo $item['name']; ?></span>
                        </div>
                        <?php if ($item['expandable']): ?>
                        <div class="admin-dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($item['submenu'])): ?>
                    <ul class="admin-submenu <?php echo $item['active'] ? 'expanded' : ''; ?>">
                        <?php foreach ($item['submenu'] as $subitem): ?>
                        <li class="admin-nav-item">
                            <a href="<?php echo $subitem['url']; ?>" class="admin-submenu-item <?php echo isset($subitem['active']) && $subitem['active'] ? 'active' : ''; ?>">
                                <div class="admin-submenu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="12" r="4"/>
                                    </svg>
                                </div>
                                <span><?php echo $subitem['name']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
                
                <li class="admin-nav-item admin-logout-section">
                    <div class="admin-nav-link">
                        <div class="admin-nav-link-content">
                            <div class="admin-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                </svg>
                            </div>
                            <span>Log out</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main-content">
            <div class="admin-header">
                <h1 class="admin-welcome-text">Welcome, <?php echo $admin_name; ?></h1>
                <div class="admin-header-right">
                    <div class="admin-search-container">
                        <div class="admin-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </div>
                        <input type="text" class="admin-search-input" placeholder="Search audit logs...">
                    </div>
                    <div class="admin-filter-container">
                        <button class="admin-filter-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                    <div class="admin-avatar">A</div>
                </div>
            </div>

            <div class="admin-dashboard-section">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">Audit Logs</h2>
                </div>

                <div class="admin-table-controls">
                    <div class="admin-entries-control">
                        <span>Show</span>
                        <select class="admin-entries-select">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <button class="admin-export-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                        </svg>
                        Export CSV
                    </button>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>User Role</th>
                                <th>Action Made</th>
                                <th>Time Stamp</th>
                                <th>Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($auditLogs as $log): 
                                $actionType = explode(' ', $log['action_made'])[0];
                                $actionColor = $actionColors[$actionType] ?? '#6c757d';
                            ?>
                            <tr>
                                <td><?php echo $log['log_id']; ?></td>
                                <td><?php echo $log['user_role']; ?></td>
                                <td>
                                    <span class="admin-action-badge" style="background-color: <?php echo $actionColor; ?>; color: white;">
                                        <?php echo $log['action_made']; ?>
                                    </span>
                                </td>
                                <td><?php echo $log['time_stamp']; ?></td>
                                <td>
                                    <span class="admin-device-badge">
                                        <?php echo $log['device']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="admin-table-footer">
                    <div class="admin-entries-info">
                        Showing 1 to <?php echo count($auditLogs); ?> of <?php echo count($auditLogs); ?> entries
                    </div>
                    <nav class="admin-pagination">
                        <ul style="display: flex; list-style: none; gap: 5px;">
                            <li><a href="#" class="admin-page-link">Previous</a></li>
                            <li class="admin-page-item active"><a href="#" class="admin-page-link">1</a></li>
                            <li><a href="#" class="admin-page-link">2</a></li>
                            <li><a href="#" class="admin-page-link">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <button class="admin-mobile-menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>

    <script>
        // JavaScript to handle submenu toggling
        document.querySelectorAll('.admin-nav-link.expandable').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                if (submenu && submenu.classList.contains('admin-submenu')) {
                    submenu.classList.toggle('expanded');
                    this.classList.toggle('expanded');
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.admin-mobile-menu');
        const sidebar = document.querySelector('.admin-sidebar');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Search functionality
        const searchInput = document.querySelector('.admin-search-input');
        const tableRows = document.querySelectorAll('.admin-table tbody tr');

        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Entries select functionality
        const entriesSelect = document.querySelector('.admin-entries-select');
        
        entriesSelect.addEventListener('change', function() {
            // In a real application, this would reload the table with the selected number of entries
            console.log('Show ' + this.value + ' entries');
        });

        // Export button functionality
        document.querySelector('.admin-export-btn').addEventListener('click', function() {
            alert('Exporting audit logs to CSV file...');
        });

        // Filter button functionality
        document.querySelector('.admin-filter-btn').addEventListener('click', function() {
            alert('Opening filter options...');
        });

        // Sort functionality (by time stamp)
        let sortDirection = 'desc';
        document.querySelector('th:nth-child(4)').addEventListener('click', function() {
            const rows = Array.from(tableRows);
            const sortedRows = rows.sort((a, b) => {
                const dateA = new Date(a.cells[3].textContent);
                const dateB = new Date(b.cells[3].textContent);
                return sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
            });
            
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            
            const tbody = document.querySelector('.admin-table tbody');
            tbody.innerHTML = '';
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    </script>
</body>
</html>