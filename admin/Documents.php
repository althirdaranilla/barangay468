<?php
// Simulating user authentication - in a real application, this would come from session
$isAdmin = true;
$adminName = "Admin";

// Sample clearance request data
$clearanceRequests = [
    [
        'clearance_id' => 'C1-2024-0001',
        'fullname' => 'Kobe Tayco',
        'email' => 'tayco@gmail.com',
        'purpose' => 'Work',
        'date_requested' => '10/24/2024',
        'date_issued' => '10/24/2024',
        'validity' => '1/24/2025',
        'status' => 'Printing'
    ],
    [
        'clearance_id' => 'C1-2024-0002',
        'fullname' => 'Edrian Valdez',
        'email' => 'valdez@gmail.com',
        'purpose' => 'Visa',
        'date_requested' => '10/22/2024',
        'date_issued' => '10/24/2024',
        'validity' => '1/24/2025',
        'status' => 'Approved'
    ],
    [
        'clearance_id' => 'C1-2024-0003',
        'fullname' => 'Althief Aranilla',
        'email' => 'aranilla@gmail.com',
        'purpose' => 'Work',
        'date_requested' => '10/20/2024',
        'date_issued' => '10/24/2024',
        'validity' => '1/24/2025',
        'status' => 'Rejected'
    ],
    [
        'clearance_id' => 'C1-2024-0004',
        'fullname' => 'Christian Somera',
        'email' => 'somera@gmail.com',
        'purpose' => 'Work',
        'date_requested' => '10/20/2024',
        'date_issued' => '10/24/2024',
        'validity' => '1/24/2025',
        'status' => 'Approved'
    ],
    [
        'clearance_id' => 'C1-2024-0005',
        'fullname' => 'Mark de Guzman',
        'email' => 'mark@gmail.com',
        'purpose' => 'Work',
        'date_requested' => '10/19/2024',
        'date_issued' => '10/24/2024',
        'validity' => '1/24/2025',
        'status' => 'Approved'
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
                'url' => '../admin/AdminManageOfficials.php',
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
        'active' => true,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Clearance Request',
                'url' => 'ClearanceRequest.php',
                'icon' => 'circle',
                'active' => true
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
        'url' => '#',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ]
];

// Status colors
$statusColors = [
    'Printing' => '#17a2b8',
    'Approved' => '#28a745',
    'Rejected' => '#dc3545',
    'Pending' => '#ffc107'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Clearance Requests</title>
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

        .admin-table-container {
            overflow-x: auto;
            margin-bottom: 20px;
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

        .admin-status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 80px;
        }

        .admin-action-buttons {
            display: flex;
            gap: 10px;
        }

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

        .admin-btn-edit {
            color: #3498db;
        }

        .admin-btn-edit:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .admin-btn-delete {
            color: #e74c3c;
        }

        .admin-btn-delete:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .admin-btn-print {
            color: #17a2b8;
        }

        .admin-btn-print:hover {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .admin-action-icon {
            width: 16px;
            height: 16px;
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
                            <span>Logout</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main-content">
            <div class="admin-header">
                <h1 class="admin-welcome-text">Welcome, <?php echo $adminName; ?></h1>
                <div class="admin-header-right">
                    <div class="admin-search-container">
                        <div class="admin-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </div>
                        <input type="text" class="admin-search-input" placeholder="Search clearance requests...">
                    </div>
                    <div class="admin-avatar">A</div>
                </div>
            </div>

            <div class="admin-dashboard-section">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">List of Clearance Request</h2>
                    <div class="admin-table-controls">
                        <div class="admin-entries-control">
                            <span>Show</span>
                            <select class="admin-entries-select">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span>entries</span>
                        </div>
                    </div>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Clearance ID</th>
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Purpose</th>
                                <th>Date Requested</th>
                                <th>Date Issued</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clearanceRequests as $request): ?>
                            <tr>
                                <td><?php echo $request['clearance_id']; ?></td>
                                <td><?php echo $request['fullname']; ?></td>
                                <td><?php echo $request['email']; ?></td>
                                <td><?php echo $request['purpose']; ?></td>
                                <td><?php echo $request['date_requested']; ?></td>
                                <td><?php echo $request['date_issued']; ?></td>
                                <td><?php echo $request['validity']; ?></td>
                                <td>
                                    <span class="admin-status-badge" style="background-color: <?php echo $statusColors[$request['status']]; ?>; color: white;">
                                        <?php echo $request['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-action-buttons">
                                        <button class="admin-btn-action admin-btn-edit" title="Edit">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                        </button>
                                        <button class="admin-btn-action admin-btn-print" title="Print">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                                            </svg>
                                        </button>
                                        <button class="admin-btn-action admin-btn-delete" title="Delete">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="admin-table-footer">
                    <div class="admin-entries-info">
                        Showing 1 to <?php echo count($clearanceRequests); ?> of <?php echo count($clearanceRequests); ?> entries
                    </div>
                    <nav class="admin-pagination">
                        <ul style="display: flex; list-style: none; gap: 5px;">
                            <li><a href="#" class="admin-page-link">Previous</a></li>
                            <li class="admin-page-item active"><a href="#" class="admin-page-link">1</a></li>
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
    </script>
</body>
</html>