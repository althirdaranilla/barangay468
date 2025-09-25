<?php
$host = 'localhost';
$dbname = 'barangay468_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Navigation menu items
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
        'active' => true,
        'expandable' => true,
        'submenu' => [
            [
                'name' => 'Manage Resident Records',
                'url' => 'AdminResidents.php',
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

// Form options
$civil_statuses = ['Single', 'Married', 'Divorced', 'Widowed', 'Separated'];
$genders = ['Male', 'Female', 'Other'];
$voter_statuses = ['Registered', 'Not Registered', 'Pending'];
$pwd_options = ['Yes', 'No'];
$religions = ['Catholic', 'Christian', 'Muslim', 'Buddhist', 'Hindu', 'Other'];
$housing_types = ['Owned', 'Rented', 'Shared', 'Other'];
$employment_statuses = ['Employed', 'Self-Employed', 'Unemployed', 'Retired'];
$senior_options = ['Yes', 'No'];
$solo_parent_options = ['Yes', 'No'];
$ofw_options = ['Yes', 'No'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare data
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
        $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);
        $birthplace = filter_input(INPUT_POST, 'birthplace', FILTER_SANITIZE_STRING);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $civil_status = filter_input(INPUT_POST, 'civil_status', FILTER_SANITIZE_STRING);
        $citizenship = filter_input(INPUT_POST, 'citizenship', FILTER_SANITIZE_STRING);
        $religion = filter_input(INPUT_POST, 'religion', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $prev_address = filter_input(INPUT_POST, 'prev_address', FILTER_SANITIZE_STRING);
        $household_no = filter_input(INPUT_POST, 'household_no', FILTER_SANITIZE_STRING);
        $household_head = filter_input(INPUT_POST, 'household_head', FILTER_SANITIZE_STRING);
        $household_head_name = filter_input(INPUT_POST, 'household_head_name', FILTER_SANITIZE_STRING);
        $relationship = filter_input(INPUT_POST, 'relationship', FILTER_SANITIZE_STRING);
        $household_members = filter_input(INPUT_POST, 'household_members', FILTER_SANITIZE_NUMBER_INT);
        $housing_type = filter_input(INPUT_POST, 'housing_type', FILTER_SANITIZE_STRING);
        $income = filter_input(INPUT_POST, 'income', FILTER_SANITIZE_NUMBER_INT);
        $voter_status = filter_input(INPUT_POST, 'voter_status', FILTER_SANITIZE_STRING);
        $precinct_no = filter_input(INPUT_POST, 'precinct_no', FILTER_SANITIZE_STRING);
        $occupation = filter_input(INPUT_POST, 'occupation', FILTER_SANITIZE_STRING);
        $employer = filter_input(INPUT_POST, 'employer', FILTER_SANITIZE_STRING);
        $employment_status = filter_input(INPUT_POST, 'employment_status', FILTER_SANITIZE_STRING);
        $senior = filter_input(INPUT_POST, 'senior', FILTER_SANITIZE_STRING);
        $pwd = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING);
        $disability_type = filter_input(INPUT_POST, 'disability_type', FILTER_SANITIZE_STRING);
        $solo_parent = filter_input(INPUT_POST, 'solo_parent', FILTER_SANITIZE_STRING);
        $four_ps_id = filter_input(INPUT_POST, 'four_ps_id', FILTER_SANITIZE_STRING);
        $indigenous_group = filter_input(INPUT_POST, 'indigenous_group', FILTER_SANITIZE_STRING);
        $ofw = filter_input(INPUT_POST, 'ofw', FILTER_SANITIZE_STRING);
        $brgy_id = filter_input(INPUT_POST, 'brgy_id', FILTER_SANITIZE_STRING);
        $gov_id_type = filter_input(INPUT_POST, 'gov_id_type', FILTER_SANITIZE_STRING);
        $gov_id_no = filter_input(INPUT_POST, 'gov_id_no', FILTER_SANITIZE_STRING);

        // Handle file uploads
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $profile_pic = '';
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $profile_pic = $upload_dir . uniqid() . '_' . basename($_FILES['profile_pic']['name']);
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic);
        }

        $valid_id = '';
        if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] == UPLOAD_ERR_OK) {
            $valid_id = $upload_dir . uniqid() . '_' . basename($_FILES['valid_id']['name']);
            move_uploaded_file($_FILES['valid_id']['tmp_name'], $valid_id);
        }

        $signature = '';
        if (isset($_FILES['signature']) && $_FILES['signature']['error'] == UPLOAD_ERR_OK) {
            $signature = $upload_dir . uniqid() . '_' . basename($_FILES['signature']['name']);
            move_uploaded_file($_FILES['signature']['tmp_name'], $signature);
        }

        // Insert into database
        $stmt = $conn->prepare("
            INSERT INTO residents (
                first_name, middle_name, last_name, suffix, birthdate, birthplace, gender, civil_status, citizenship, religion,
                email, contact, address, prev_address, household_no, household_head, household_head_name, relationship,
                household_members, housing_type, income, voter_status, precinct_no, occupation, employer, employment_status,
                senior, pwd, disability_type, solo_parent, four_ps_id, indigenous_group, ofw, brgy_id, gov_id_type, gov_id_no,
                profile_pic, valid_id, signature
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssssssssssssssisssssssssssssssssss",
            $first_name, $middle_name, $last_name, $suffix, $birthdate, $birthplace, $gender, $civil_status, $citizenship, $religion,
            $email, $contact, $address, $prev_address, $household_no, $household_head, $household_head_name, $relationship,
            $household_members, $housing_type, $income, $voter_status, $precinct_no, $occupation, $employer, $employment_status,
            $senior, $pwd, $disability_type, $solo_parent, $four_ps_id, $indigenous_group, $ofw, $brgy_id, $gov_id_type, $gov_id_no,
            $profile_pic, $valid_id, $signature
        );

        if ($stmt->execute()) {
            $success_message = "Resident added successfully!";
        } else {
            $error_message = "Error adding resident: " . $stmt->error;
        }
        $stmt->close();
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Resident</title>
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
            overflow-x: hidden;
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
            width: calc(100% - 280px);
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
            width: 100%;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
        }

        .admin-form-group {
            margin-bottom: 20px;
        }

        .admin-form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
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

        .admin-form-section-title {
            grid-column: 1 / -1;
            margin: 20px 0 10px;
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 18px;
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

        .admin-webcam-section {
            grid-column: 1 / -1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #e0e0e0;
            text-align: center;
        }

        .admin-webcam-section h4 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .admin-webcam-placeholder {
            width: 200px;
            height: 150px;
            background: #e0e0e0;
            border-radius: 8px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-weight: 600;
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

        .admin-message {
            grid-column: 1 / -1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .admin-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .admin-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 1200px) {
            .admin-form-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .admin-form-container {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease;
                z-index: 1000;
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
                padding: 70px 15px 15px;
            }

            .admin-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 15px;
            }
            
            .admin-dashboard-section {
                padding: 20px;
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
                    <svg xmlns="http:// Constructing the document...
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
                    <h2 class="admin-section-title">Add Resident</h2>
                    <p class="admin-section-subtitle">Register a new resident in the barangay system</p>
                </div>

                <?php if (isset($success_message)): ?>
                <div class="admin-message success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                <div class="admin-message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form id="addResidentForm" class="admin-form-container" method="post" enctype="multipart/form-data">

                <!-- Personal Information -->
                <h3 class="admin-form-section-title">Personal Information</h3>

                <div class="admin-form-group">
                    <label class="admin-form-label">First Name</label>
                    <input type="text" name="first_name" class="admin-form-input" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Last Name</label>
                    <input type="text" name="last_name" class="admin-form-input" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Suffix</label>
                    <input type="text" name="suffix" class="admin-form-input" placeholder="Jr., Sr., III">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Birthdate</label>
                    <input type="date" name="birthdate" class="admin-form-input" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Place of Birth</label>
                    <input type="text" name="birthplace" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Gender</label>
                    <select name="gender" class="admin-form-select" required>
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($genders as $g): ?>
                            <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Civil Status</label>
                    <select name="civil_status" class="admin-form-select" required>
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($civil_statuses as $c): ?>
                            <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Citizenship</label>
                    <input type="text" name="citizenship" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Religion</label>
                    <select name="religion" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($religions as $r): ?>
                            <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Contact Information -->
                <h3 class="admin-form-section-title">Contact Information</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Email</label>
                    <input type="email" name="email" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Contact Number</label>
                    <input type="tel" name="contact" class="admin-form-input" required>
                </div>
                <div class="admin-form-group admin-form-fullwidth">
                    <label class="admin-form-label">Complete Address</label>
                    <input type="text" name="address" class="admin-form-input" required>
                </div>
                <div class="admin-form-group admin-form-fullwidth">
                    <label class="admin-form-label">Previous Address</label>
                    <input type="text" name="prev_address" class="admin-form-input">
                </div>

                <!-- Household Information -->
                <h3 class="admin-form-section-title">Household Information</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Household Number / ID</label>
                    <input type="text" name="household_no" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Household Head?</label>
                    <select name="household_head" class="admin-form-select">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Name of Household Head</label>
                    <input type="text" name="household_head_name" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Relationship to Household Head</label>
                    <input type="text" name="relationship" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">No. of Household Members</label>
                    <input type="number" name="household_members" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Type of Housing</label>
                    <select name="housing_type" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($housing_types as $h): ?>
                            <option value="<?php echo $h; ?>"><?php echo $h; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Monthly Household Income</label>
                    <input type="number" name="income" class="admin-form-input">
                </div>

                <!-- Community Information -->
                <h3 class="admin-form-section-title">Community Information</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Voter Status</label>
                    <select name="voter_status" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($voter_statuses as $v): ?>
                            <option value="<?php echo $v; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Precinct Number</label>
                    <input type="text" name="precinct_no" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Occupation</label>
                    <input type="text" name="occupation" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Employer / Business</label>
                    <input type="text" name="employer" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Employment Status</label>
                    <select name="employment_status" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($employment_statuses as $e): ?>
                            <option value="<?php echo $e; ?>"><?php echo $e; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Special Categories -->
                <h3 class="admin-form-section-title">Special Categories</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Senior Citizen</label>
                    <select name="senior" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($senior_options as $s): ?>
                            <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">PWD</label>
                    <select name="pwd" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($pwd_options as $p): ?>
                            <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Type of Disability</label>
                    <input type="text" name="disability_type" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Solo Parent</label>
                    <select name="solo_parent" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($solo_parent_options as $sp): ?>
                            <option value="<?php echo $sp; ?>"><?php echo $sp; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">4Ps Beneficiary</label>
                    <input type="text" name="four_ps_id" class="admin-form-input" placeholder="Enter Household ID if applicable">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Indigenous Group</label>
                    <input type="text" name="indigenous_group" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">OFW Household Member</label>
                    <select name="ofw" class="admin-form-select">
                        <option value="" disabled selected>Select</option>
                        <?php foreach ($ofw_options as $o): ?>
                            <option value="<?php echo $o; ?>"><?php echo $o; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Identification -->
                <h3 class="admin-form-section-title">Identification</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Barangay ID No.</label>
                    <input type="text" name="brgy_id" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Government ID Type</label>
                    <input type="text" name="gov_id_type" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Government ID Number</label>
                    <input type="text" name="gov_id_no" class="admin-form-input">
                </div>

                <!-- Attachments -->
                <h3 class="admin-form-section-title">Attachments</h3>
                <div class="admin-form-group">
                    <label class="admin-form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" accept="image/*" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Upload Valid ID / Proof of Residency</label>
                    <input type="file" name="valid_id" accept="image/*,.pdf" class="admin-form-input">
                </div>
                <div class="admin-form-group admin-form-fullwidth">
                    <label class="admin-form-label">Digital Signature</label>
                    <input type="file" name="signature" accept="image/*" class="admin-form-input">
                </div>

                <!-- Submit -->
                <div class="admin-form-actions">
                    <button type="submit" class="admin-btn-submit">
                        Add Resident
                    </button>
                </div>
            </form>
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
        const addResidentForm = document.getElementById('addResidentForm');
        
        addResidentForm.addEventListener('submit', function(e) {
            // Remove client-side validation since server-side validation is handled
            // e.preventDefault();
            
            // Basic form validation
            let isValid = true;
            const requiredFields = addResidentForm.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '#e0e0e0';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Input validation
        const inputs = addResidentForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.style.borderColor = '#e0e0e0';
            });
        });
    </script>
</body>
</html>