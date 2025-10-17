<?php
// Simulating user authentication - in a real application, this would come from session
require "../database/connection.php";
$isAdmin = true;
$adminName = $_SESSION["user_name"];
$user_role = $_SESSION["user_position"];
// Sample announcements data
$announcements = [
    [
        'id' => 'ANN-001',
        'author' => 'Barangay Chairman',
        'title' => 'Community Clean-up Drive',
        'date' => '08/28/2025',
        'description' => 'Join us for a community clean-up drive this Saturday from 8AM to 12PM. All residents are encouraged to participate. Gloves and garbage bags will be provided.',
        'status' => 'Published'
    ],
    [
        'id' => 'ANN-002',
        'author' => 'Health Officer',
        'title' => 'Free Vaccination Program',
        'date' => '08/30/2025',
        'description' => 'Free vaccination program for children and senior citizens will be held at the barangay hall on September 5-6, 2025. Please bring your vaccination records.',
        'status' => 'Published'
    ],
    [
        'id' => 'ANN-003',
        'author' => 'Admin',
        'title' => 'Water Interruption Schedule',
        'date' => '09/02/2025',
        'description' => 'There will be a water service interruption on September 5, 2025 from 9AM to 3PM due to pipeline maintenance. Please store enough water for your needs.',
        'status' => 'Draft'
    ],
    [
        'id' => 'ANN-004',
        'author' => 'SK Chairman',
        'title' => 'Youth Basketball Tournament',
        'date' => '09/05/2025',
        'description' => 'Registration for the annual youth basketball tournament is now open. Teams must register by September 15 at the SK office.',
        'status' => 'Published'
    ],
    [
        'id' => 'ANN-005',
        'author' => 'Barangay Secretary',
        'title' => 'New Business Permit Requirements',
        'date' => '09/10/2025',
        'description' => 'Please be informed of the new requirements for business permit applications starting next fiscal year. Visit the barangay office for more details.',
        'status' => 'Scheduled'
    ]
];
$sql_announcements = "SELECT * FROM announcements ORDER BY date DESC";
$result_announcements = $conn->query($sql_announcements);
if ($result_announcements === false) {
    die("Error retrieving announcements: " . $conn->error);
}

$announcements = [];
while ($row = $result_announcements->fetch_assoc()) {
    $announcements[] = $row;
}
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
        'active' => true,
        'expandable' => false,
        'submenu' => []
    ]
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['publish']) || isset($_POST['save'])){
        $status = "Draft";
        if(isset($_POST['publish'])){
            $status = "Published";
        }

        $image_path = ""; // Initialize image_path as an empty string
        $upload_dir = "../Announcements/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir);
        }
        // Check if an image has been uploaded
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
            $image_tmp = $_FILES['poster']['tmp_name'];
            $image_name = $_FILES['poster']['name']; // Define your uploads folder
            $image_path = $upload_dir . basename($image_name);
            $imageFileType = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
            $is_valid_img = in_array($imageFileType, array("jpg", "png", "jpeg", "gif", "jfif", "tiff"));
            $img_exists = file_exists($image_path);

            if (!$img_exists && $is_valid_img) {
                if (!move_uploaded_file($image_tmp, $image_path)) {
                    echo "<script>alert('Error uploading image.');</script>";
                }
            } else {
                if (!$is_valid_img) {
                    echo "<script>alert('Invalid Image Type.');</script>";
                } else {
                    echo "<script>alert('Image already exists.');</script>";
                }
            }
        }
        $stmt = $conn->prepare("INSERT INTO announcements (number) VALUES (NULL) ");
        if ($stmt->execute()) {
            $last_id = $conn->insert_id;
            $hex_id = sprintf('%03X', $last_id);
            $id = "ANN-" . $hex_id;
            $author = $user_role;
            $title = $_POST["title"];
            $date = $_POST["date"];
            $description = $_POST["description"];
            $stmt = $conn->prepare("UPDATE announcements SET id=?, author=?, title=?, date=?, description=?, status=?, poster=? WHERE number=?");
            $stmt->bind_param("ssssssss", $id, $author, $title, $date, $description, $status, $image_path, $last_id);
            if ($stmt->execute()) {
                echo "<script>console.log('Announcement created.');</script>";
                log_activity($user_role, "Added", "an Announcement", $conn);
            } else {
                echo "<script>console.log('Failed to create announcement.');</script>";
            }
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF']);
            die;
        } else {
            $stmt->close();
            echo "<script>console.log('Failed to create announcement.');</script>";
        }

    } else if(isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id=?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            echo "<script>console.log('Announcement Deleted.');</script>";
            log_activity($user_role, "Deleted", "an Announcement", $conn);
        } else {
            echo "<script>console.log('Failed to create announcement.');</script>";
        }
    } else if(isset($_POST['hide'])){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $new_status = $status != "Hidden" ? "Hidden" : "Published";
        $stmt = $conn->prepare("UPDATE announcements SET status=? WHERE id=?");
        $stmt->bind_param("ss", $new_status, $id);
        if ($stmt->execute()) {
            echo "<script>console.log('Announcement Deleted.');</script>";
            log_activity($user_role, "Updated", "an Announcement", $conn);
        } else {
            echo "<script>console.log('Failed to create announcement.');</script>";
        }
    }
}
// Status colors
$statusColors = [
    'Published' => '#28a745',
    'Draft' => '#6c757d',
    'Scheduled' => '#17a2b8'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Announcements</title>
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
            margin-bottom: 30px;
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

        .admin-add-announcement-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-add-announcement-btn:hover {
            background: #218838;
        }

        .admin-announcement-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .admin-form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .admin-form-group {
            margin-bottom: 15px;
        }

        .admin-form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #495057;
        }

        .admin-form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }

        .admin-form-input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .admin-form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            min-height: 120px;
            resize: vertical;
        }

        .admin-form-textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .admin-form-divider {
            height: 1px;
            background: #dee2e6;
            margin: 20px 0;
        }

        .admin-file-upload {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-file-input {
            display: none;
        }

        .admin-file-label {
            background: #e9ecef;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
        }

        .admin-file-name {
            font-size: 14px;
            color: #6c757d;
        }

        .admin-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .admin-form-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .admin-form-submit {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .admin-form-save {
            background: #218838;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
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

        .admin-status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            color: white;
        }

        .admin-action-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin: 0 5px;
        }

        .admin-action-icon {
            width: 16px;
            height: 16px;
            color: #6c757d;
        }

        .admin-action-btn:hover .admin-action-icon {
            color: #007bff;
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

            .admin-form-grid {
                grid-template-columns: 1fr;
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
                    <a href="<?php echo $item['url']; ?>" class="admin-nav-link <?php echo $item['expandable'] ? 'expandable' : ''; ?> <?php echo $item['active'] ? 'active' : ''; ?>">
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
                    </a>
                    
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
                <h1 class="admin-welcome-text">Welcome, <?php echo $adminName; ?></h1>
                <div class="admin-header-right">
                    <div class="admin-search-container">
                        <div class="admin-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </div>
                        <input type="text" class="admin-search-input" placeholder="Search announcements...">
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

            <!-- Add Announcement Form -->
            <div class="admin-dashboard-section">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">Add Announcement</h2>
                </div>

                <form class="admin-announcement-form" method="POST">
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Author:</label>
                            <input type="text" name="author" class="admin-form-input" placeholder="Enter Author" value="<?php echo $user_role ?>">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Title:</label>
                            <input type="text" name="title" class="admin-form-input" placeholder="Enter title">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Date:</label>
                            <input type="date" name="date" class="admin-form-input" id="date-picker" placeholder="mm/dd/yy">
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Description:</label>
                        <textarea class="admin-form-textarea" name="description" placeholder="Enter Content"></textarea>
                    </div>

                    <div class="admin-form-divider"></div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Upload Poster:</label>
                        <div class="admin-file-upload">
                            <input type="file" id="poster-upload" name="poster" class="admin-file-input">
                            <label for="poster-upload" class="admin-file-label">Upload File</label>
                            <span class="admin-file-name">No file chosen</span>
                        </div>
                    </div>

                    <div class="admin-form-actions">
                        <button type="button" class="admin-form-cancel">Cancel</button>
                        <button type="submit" name="publish" class="admin-form-submit">Publish Announcement</button>
                        <button type="submit" name="save" class="admin-form-save">Save Draft</button>
                    </div>
                </form>
            </div>

            <!-- Announcements List -->
            <div class="admin-dashboard-section">
                <div class="admin-section-header">
                    <h2 class="admin-section-title">Announcements</h2>
                    <button class="admin-add-announcement-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Add New
                    </button>
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
                                <th>ID</th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcements as $announcement): 
                                $statusColor = $statusColors[$announcement['status']] ?? '#6c757d';
                            ?>
                            <tr>
                                <td><?php echo $announcement['id']; ?></td>
                                <td><?php echo $announcement['author']; ?></td>
                                <td><?php echo $announcement['title']; ?></td>
                                <td><?php echo $announcement['date']; ?></td>
                                <td>
                                    <span class="admin-status-badge" style="background-color: <?php echo $statusColor; ?>;">
                                        <?php echo $announcement['status']; ?>
                                    </span>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?php echo $announcement["id"] ?>"/>
                                        <input type="hidden" name="status" value="<?php echo $announcement["status"] ?>"/>
                                        <button class="admin-action-btn" type="submit" value="hide">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                            </svg>
                                        </button>
                                        <button class="admin-action-btn" type="submit" value="edit">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                        </button>
                                        <button class="admin-action-btn" type="submit" value="delete">
                                            <svg class="admin-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="admin-table-footer">
                    <div class="admin-entries-info">
                        Showing 1 to <?php echo count($announcements); ?> of <?php echo count($announcements); ?> entries
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
        document.getElementById("date-picker").min = new Date().toISOString().split("T")[0];
        
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

        // File upload functionality
        const fileInput = document.getElementById('poster-upload');
        const fileName = document.querySelector('.admin-file-name');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file chosen';
            }
        });

        // Form cancel button
        document.querySelector('.admin-form-cancel').addEventListener('click', function() {
            document.querySelector('.admin-announcement-form').reset();
            fileName.textContent = 'No file chosen';
        });

        // Add new announcement button
        document.querySelector('.admin-add-announcement-btn').addEventListener('click', function() {
            document.querySelector('.admin-announcement-form').scrollIntoView({ behavior: 'smooth' });
        });

        // Export button functionality
        document.querySelector('.admin-export-btn').addEventListener('click', function() {
            alert('Exporting announcements to CSV file...');
        });

        // Filter button functionality
        document.querySelector('.admin-filter-btn').addEventListener('click', function() {
            alert('Opening filter options...');
        });
    </script>
</body>
</html>