<?php
// Simulating user authentication - in a real application, this would come from session
$isAdmin = true;
$adminName = "Admin";

// Navigation menu items
$nav_items = [
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

// Common purposes for clearance
$purposes = [
    'Employment',
    'Business Permit',
    'School Requirement',
    'Visa Application',
    'Travel Abroad',
    'Government Transaction',
    'Bank Requirement',
    'Loan Application',
    'NBI Clearance',
    'Police Clearance',
    'Other'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walk-in Clearance Request</title>
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
            margin-bottom: 30px;
        }

        .admin-section-header {
            margin-bottom: 25px;
        }

        .admin-section-title {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .admin-section-subtitle {
            font-size: 16px;
            color: #7f8c8d;
        }

        .admin-form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .admin-form-group {
            margin-bottom: 20px;
        }

        .admin-form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .admin-form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .admin-form-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .admin-form-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background-color: white;
            transition: border-color 0.3s ease;
        }

        .admin-form-select:focus {
            outline: none;
            border-color: #3498db;
        }

        .admin-form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s ease;
        }

        .admin-form-textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .admin-form-fullwidth {
            grid-column: 1 / -1;
        }

        .admin-form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .admin-btn-submit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-btn-submit:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .admin-search-results {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #e0e0e0;
        }

        .admin-search-results h4 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .admin-resident-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .admin-info-item {
            display: flex;
            flex-direction: column;
        }

        .admin-info-label {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .admin-info-value {
            font-weight: 600;
            color: #2c3e50;
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

        @media (max-width: 1024px) {
            .admin-form-container {
                grid-template-columns: 1fr;
            }
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
                text-align: center;
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
                            <a href="<?php echo $subitem['url']; ?>" class="admin-submenu-item">
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
                <h1 class="admin-welcome-text">Welcome, <?php echo $adminName; ?></h1>
                <div class="admin-header-right">
                    <div class="admin-avatar">A</div>
                </div>
            </div>

            <div class="admin-dashboard-section">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">Walk-in Clearance Request</h2>
                    <p class="admin-section-subtitle">Process clearance requests for walk-in residents</p>
                </div>

                <form id="clearanceForm" class="admin-form-container">
                    <!-- Search Resident Section -->
                    <div class="admin-form-fullwidth">
                        <h3 style="margin-bottom: 20px; color: #2c3e50; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">Search Resident</h3>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">First Name</label>
                        <input type="text" class="admin-form-input" placeholder="Enter first name">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Middle Name</label>
                        <input type="text" class="admin-form-input" placeholder="Enter middle name">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Last Name</label>
                        <input type="text" class="admin-form-input" placeholder="Enter last name">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Email</label>
                        <input type="email" class="admin-form-input" placeholder="sample@gmail.com">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Cellphone No.</label>
                        <input type="tel" class="admin-form-input" placeholder="Enter phone number">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Address</label>
                        <input type="text" class="admin-form-input" placeholder="Enter address">
                    </div>

                    <div class="admin-form-fullwidth">
                        <div class="admin-search-results">
                            <h4>Resident Information</h4>
                            <div class="admin-resident-info">
                                <div class="admin-info-item">
                                    <span class="admin-info-label">Full Name</span>
                                    <span class="admin-info-value">Juan Dela Cruz</span>
                                </div>
                                <div class="admin-info-item">
                                    <span class="admin-info-label">Email</span>
                                    <span class="admin-info-value">juan.delacruz@example.com</span>
                                </div>
                                <div class="admin-info-item">
                                    <span class="admin-info-label">Phone</span>
                                    <span class="admin-info-value">+63 912 345 6789</span>
                                </div>
                                <div class="admin-info-item">
                                    <span class="admin-info-label">Address</span>
                                    <span class="admin-info-value">123 Main Street, Barangay 123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clearance Details Section -->
                    <div class="admin-form-fullwidth">
                        <h3 style="margin-bottom: 20px; color: #2c3e50; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">Clearance Details</h3>
                    </div>

                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Purpose</label>
                        <select class="admin-form-select">
                            <option value="" disabled selected>Select purpose of clearance</option>
                            <?php foreach ($purposes as $purpose): ?>
                            <option value="<?php echo strtolower(str_replace(' ', '-', $purpose)); ?>"><?php echo $purpose; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Other Purpose (if not listed above)</label>
                        <input type="text" class="admin-form-input" placeholder="Specify other purpose">
                    </div>

                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Additional Notes</label>
                        <textarea class="admin-form-textarea" placeholder="Enter any additional information or notes"></textarea>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                            Add Clearance Request
                        </button>
                    </div>
                </form>
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

        // Form submission handling
        const clearanceForm = document.getElementById('clearanceForm');
        
        clearanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, this would submit the form data to the server
            alert('Clearance request submitted successfully!');
            clearanceForm.reset();
        });

        // Simulate resident search functionality
        const searchInputs = document.querySelectorAll('.admin-form-input');
        
        searchInputs.forEach(input => {
            input.addEventListener('input', function() {
                // In a real application, this would search for residents based on input
                console.log('Searching for resident with: ' + this.value);
            });
        });
    </script>
</body>
</html>