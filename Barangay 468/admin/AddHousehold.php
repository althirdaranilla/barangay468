<?php
// Simulating user authentication - in a real application, this would come from session
$isAdmin = true;
$adminName = "Admin";

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
        'active' => true,
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Household</title>
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

        .admin-form-fullwidth {
            grid-column: 1 / -1;
        }

        .admin-family-members {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
        }

        .admin-member-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin-member-form:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .admin-btn-add-member {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            height: fit-content;
        }

        .admin-btn-add-member:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .admin-member-list {
            margin-top: 20px;
        }

        .admin-member-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .admin-member-info {
            display: flex;
            flex-direction: column;
        }

        .admin-member-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .admin-member-actions {
            display: flex;
            gap: 10px;
        }

        .admin-btn-remove {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .admin-btn-remove:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .admin-members-count {
            background: #e9ecef;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            color: #495057;
            display: inline-block;
            margin-top: 10px;
        }

        .admin-form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .admin-btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-btn-cancel:hover {
            background: #5a6268;
        }

        .admin-btn-submit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
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
            
            .admin-member-form {
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
                    <h2 class="admin-section-title">Add Household</h2>
                </div>

                <form id="householdForm" class="admin-form-container">
                    <!-- Household Number -->
                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Household Number</label>
                        <input type="text" class="admin-form-input" placeholder="Enter Household Number" required>
                    </div>

                    <!-- Head of the Family -->
                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Head of the Family</label>
                        <input type="text" class="admin-form-input" placeholder="Enter head of the family" required>
                    </div>

                    <!-- Family Members Section -->
                    <div class="admin-form-group admin-form-fullwidth">
                        <label class="admin-form-label">Name of family members</label>
                        
                        <div class="admin-family-members">
                            <div class="admin-member-form">
                                <div>
                                    <label class="admin-form-label">First Name</label>
                                    <input type="text" class="admin-form-input" placeholder="Enter first name">
                                </div>
                                <div>
                                    <label class="admin-form-label">Last Name</label>
                                    <input type="text" class="admin-form-input" placeholder="Enter last name">
                                </div>
                                <button type="button" class="admin-btn-add-member">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                    </svg>
                                    Add
                                </button>
                            </div>

                            <div class="admin-member-list">
                                <!-- Sample members will be added here by JavaScript -->
                            </div>

                            <div class="admin-members-count">
                                Number of Family Members: <span id="memberCount">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="admin-form-actions">
                        <button type="button" class="admin-btn-cancel">Cancel</button>
                        <button type="submit" class="admin-btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
                            </svg>
                            Save Household
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

        // Family members management
        const memberForm = document.querySelector('.admin-member-form');
        const memberList = document.querySelector('.admin-member-list');
        const memberCount = document.getElementById('memberCount');
        const addMemberButton = document.querySelector('.admin-btn-add-member');
        let members = [];

        addMemberButton.addEventListener('click', function() {
            const firstNameInput = memberForm.querySelector('input[placeholder="Enter first name"]');
            const lastNameInput = memberForm.querySelector('input[placeholder="Enter last name"]');
            
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            
            if (firstName && lastName) {
                // Add member to array
                members.push({
                    firstName: firstName,
                    lastName: lastName,
                    id: Date.now() // Unique ID for each member
                });
                
                // Update member list
                updateMemberList();
                
                // Clear inputs
                firstNameInput.value = '';
                lastNameInput.value = '';
                
                // Focus on first name input
                firstNameInput.focus();
            } else {
                alert('Please enter both first and last name');
            }
        });

        function updateMemberList() {
            memberList.innerHTML = '';
            memberCount.textContent = members.length;
            
            members.forEach((member, index) => {
                const memberItem = document.createElement('div');
                memberItem.className = 'admin-member-item';
                memberItem.innerHTML = `
                    <div class="admin-member-info">
                        <span class="admin-member-name">${member.firstName} ${member.lastName}</span>
                    </div>
                    <div class="admin-member-actions">
                        <button type="button" class="admin-btn-remove" data-id="${member.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 13H5v-2h14v2z"/>
                            </svg>
                        </button>
                    </div>
                `;
                memberList.appendChild(memberItem);
            });
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.admin-btn-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const memberId = parseInt(this.getAttribute('data-id'));
                    members = members.filter(member => member.id !== memberId);
                    updateMemberList();
                });
            });
        }

        // Form submission
        const householdForm = document.getElementById('householdForm');
        
        householdForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (members.length === 0) {
                alert('Please add at least one family member');
                return;
            }
            
            // In a real application, this would submit the form data to the server
            alert('Household added successfully!');
            householdForm.reset();
            members = [];
            updateMemberList();
        });

        // Cancel button
        document.querySelector('.admin-btn-cancel').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
                window.location.href = 'households.php'; // Redirect to households list
            }
        });
    </script>
</body>
</html>