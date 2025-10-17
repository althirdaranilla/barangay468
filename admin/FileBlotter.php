<?php
// Simulating user authentication - in a real application, this would come from session
$isUser = true;
$userName = "User";

// Navigation menu items for user
$nav_items = [
    [
        'name' => 'Dashboard',
        'icon' => 'dashboard',
        'url' => 'UserDashboard.php',
        'active' => false,
        'expandable' => false,
        'submenu' => []
    ],
    [
        'name' => 'File Blotter',
        'icon' => 'document',
        'url' => '#',
        'active' => true,
        'expandable' => false,
        'submenu' => []
    ],
    [
        'name' => 'Request Documents',
        'icon' => 'request',
        'url' => '#',
        'active' => false,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Barangay Clearance',
                'url' => 'ClearanceRequest.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Barangay Permit',
                'url' => 'PermitRequest.php',
                'icon' => 'circle'
            ],
            [
                'name' => 'Certificate',
                'url' => 'CertificateRequest.php',
                'icon' => 'circle'
            ]
        ]
    ],
    [
        'name' => 'My Profile',
        'icon' => 'profile',
        'url' => '#',
        'active' => false,
        'expandable' => false,
        'submenu' => []
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
    <title>File Blotter</title>
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

        .user-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            overflow-y: auto;
            height: 100vh;
            z-index: 1000;
        }

        .user-logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .user-logo-icon {
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

        .user-logo-text {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .user-nav-menu {
            list-style: none;
        }

        .user-nav-item {
            margin-bottom: 2px;
        }

        .user-nav-link {
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

        .user-nav-link:hover, .user-nav-link.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #fff;
            color: #fff;
        }

        .user-nav-link.expandable {
            justify-content: space-between;
        }

        .user-nav-link-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .user-nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            color: rgba(255,255,255,0.9);
            flex-shrink: 0;
        }

        .user-nav-icon svg {
            width: 100%;
            height: 100%;
        }

        .user-dropdown-icon {
            width: 16px;
            height: 16px;
            color: rgba(255,255,255,0.7);
            transition: transform 0.3s ease;
        }

        .user-nav-link.expanded .user-dropdown-icon {
            transform: rotate(180deg);
        }

        .user-submenu {
            max-height: 0;
            overflow: hidden;
            background: rgba(0,0,0,0.1);
            transition: max-height 0.3s ease;
        }

        .user-submenu.expanded {
            max-height: 1000px;
        }

        .user-submenu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px 12px 60px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .user-submenu-item:hover, .user-submenu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: rgba(255,255,255,0.5);
            color: #fff;
        }

        .user-submenu-icon {
            width: 12px;
            height: 12px;
            margin-right: 12px;
            color: rgba(255,255,255,0.6);
        }

        .user-logout-section {
            margin-top: 30px;
        }

        .user-main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        .user-header {
            background: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .user-welcome-text {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .user-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
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

        .user-dashboard-section {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .user-section-header {
            margin-bottom: 25px;
        }

        .user-section-title {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .user-form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .user-form-group {
            margin-bottom: 20px;
        }

        .user-form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .user-form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .user-form-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .user-form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s ease;
        }

        .user-form-textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .user-form-fullwidth {
            grid-column: 1 / -1;
        }

        .user-date-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-date-text {
            color: #6c757d;
            font-size: 14px;
            min-width: 80px;
        }

        .user-form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .user-btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-btn-cancel:hover {
            background: #5a6268;
        }

        .user-btn-submit {
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

        .user-btn-submit:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .user-mobile-menu {
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
            .user-form-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .user-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease;
            }

            .user-sidebar.active {
                left: 0;
            }

            .user-mobile-menu {
                display: block;
            }

            .user-main-content {
                margin-left: 0;
                width: 100%;
            }

            .user-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="user-container">
        <!-- Sidebar -->
        <div class="user-sidebar">
            <div class="user-logo">
                <div class="user-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 5.5V7H9V5.5L3 7V9L9 10.5V12L3 13.5V15.5L9 14V16H15V14L21 15.5V13.5L15 12V10.5L21 9Z" />
                    </svg>
                </div>
                <div class="user-logo-text">
                    Barangay System<br>User Portal
                </div>
            </div>

            <ul class="user-nav-menu">
                <?php foreach ($nav_items as $item): ?>
                <li class="user-nav-item">
                    <div class="user-nav-link <?php echo $item['expandable'] ? 'expandable' : ''; ?> <?php echo $item['active'] ? 'active' : ''; ?>">
                        <div class="user-nav-link-content">
                            <div class="user-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                            <span><?php echo $item['name']; ?></span>
                        </div>
                        <?php if ($item['expandable']): ?>
                        <div class="user-dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($item['submenu'])): ?>
                    <ul class="user-submenu <?php echo $item['active'] ? 'expanded' : ''; ?>">
                        <?php foreach ($item['submenu'] as $subitem): ?>
                        <li class="user-nav-item">
                            <a href="<?php echo $subitem['url']; ?>" class="user-submenu-item">
                                <div class="user-submenu-icon">
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
                
                <li class="user-nav-item user-logout-section">
                    <div class="user-nav-link">
                        <div class="user-nav-link-content">
                            <div class="user-nav-icon">
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
        <div class="user-main-content">
            <div class="user-header">
                <h1 class="user-welcome-text">Welcome, <?php echo $userName; ?></h1>
                <div class="user-header-right">
                    <div class="user-avatar">U</div>
                </div>
            </div>

            <div class="user-dashboard-section">
                <div class="user-section-header">
                    <h2 class="user-section-title">File Blotter</h2>
                    <p class="user-section-subtitle">Report an incident or complaint to the barangay</p>
                </div>

                <form id="blotterForm" class="user-form-container">
                    <!-- Complainant Information -->
                    <div class="user-form-fullwidth">
                        <h3 style="margin-bottom: 20px; color: #2c3e50; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">Complainant Information</h3>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">First Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter first name" required>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Middle Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter middle name">
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Last Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter last name" required>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Contact Number</label>
                        <input type="tel" class="user-form-input" placeholder="Enter Contact No." required>
                    </div>

                    <!-- Incident Details -->
                    <div class="user-form-fullwidth">
                        <h3 style="margin-bottom: 20px; color: #2c3e50; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">Incident Details</h3>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Date of Incident</label>
                        <div class="user-date-input">
                            <span class="user-date-text">mm/dd/yy</span>
                            <input type="date" class="user-form-input" required>
                        </div>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Date Reported</label>
                        <div class="user-date-input">
                            <span class="user-date-text">mm/dd/yy</span>
                            <input type="date" class="user-form-input" required>
                        </div>
                    </div>

                    <div class="user-form-group user-form-fullwidth">
                        <label class="user-form-label">Description of the Incident</label>
                        <textarea class="user-form-textarea" placeholder="Please provide a detailed description of what happened, including the location, people involved, and any other relevant information." required></textarea>
                    </div>

                    <!-- Respondent Information -->
                    <div class="user-form-fullwidth">
                        <h3 style="margin-bottom: 20px; color: #2c3e50; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">Respondent Information (if known)</h3>
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Respondent First Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter first name">
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Respondent Middle Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter middle name">
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Respondent Last Name</label>
                        <input type="text" class="user-form-input" placeholder="Enter last name">
                    </div>

                    <div class="user-form-group">
                        <label class="user-form-label">Respondent Address</label>
                        <input type="text" class="user-form-input" placeholder="Enter address">
                    </div>

                    <!-- Form Actions -->
                    <div class="user-form-actions">
                        <button type="button" class="user-btn-cancel">Cancel</button>
                        <button type="submit" class="user-btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
                            </svg>
                            Submit Blotter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <button class="user-mobile-menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>

    <script>
        // JavaScript to handle submenu toggling
        document.querySelectorAll('.user-nav-link.expandable').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                if (submenu && submenu.classList.contains('user-submenu')) {
                    submenu.classList.toggle('expanded');
                    this.classList.toggle('expanded');
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.user-mobile-menu');
        const sidebar = document.querySelector('.user-sidebar');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Form submission
        const blotterForm = document.getElementById('blotterForm');
        
        blotterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = blotterForm.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '#e0e0e0';
                }
            });
            
            if (valid) {
                // In a real application, this would submit the form data to the server
                alert('Blotter submitted successfully! The barangay will review your report.');
                blotterForm.reset();
            } else {
                alert('Please fill in all required fields.');
            }
        });

        // Cancel button
        document.querySelector('.user-btn-cancel').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel? All unsaved information will be lost.')) {
                blotterForm.reset();
            }
        });

        // Set current date as default for date reported
        const dateReportedInput = document.querySelector('input[type="date"]');
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        dateReportedInput.value = formattedDate;

        // Auto-format contact number
        const contactInput = document.querySelector('input[placeholder="Enter Contact No."]');
        contactInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            e.target.value = value;
        });
    </script>
</body>
</html>