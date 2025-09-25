<?php
session_start();

// Database connection configuration
$host = 'localhost';
$dbname = 'barangay468_db';
$username = 'root'; // Change this to your database username
$password = '';     // Change this to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_POST) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_type = $_POST['user_type'] ?? '';
    $position = $_POST['position'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $agree_terms = isset($_POST['agree_terms']);
    
    $errors = [];
    
    // Validation
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($user_type) || !in_array($user_type, ['official', 'resident'])) $errors[] = "Please select a valid user type";
    if ($user_type === 'official') {
        if (empty($position) || $position == 'Select Position') $errors[] = "Please select a position";
    }
    if (empty($password)) $errors[] = "Password is required";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters long";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    if (!$agree_terms) $errors[] = "You must agree to the Terms and Conditions";
    
    // Check if email already exists in either table
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                SELECT id FROM admin_users WHERE email = ?
                UNION
                SELECT id FROM residents WHERE email = ?
            ");
            $stmt->execute([$email, $email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email already exists. Please use a different email.";
            }
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            if ($user_type === 'official') {
                // Insert new admin user
                $stmt = $pdo->prepare("
                    INSERT INTO admin_users (first_name, last_name, email, position, password, created_at, status) 
                    VALUES (?, ?, ?, ?, ?, NOW(), 'active')
                ");
                
                $result = $stmt->execute([
                    $first_name,
                    $last_name, 
                    $email,
                    $position,
                    $hashed_password
                ]);
            } else {
                // Insert new resident user (assuming 'residents' table exists with similar structure minus position)
                $stmt = $pdo->prepare("
                    INSERT INTO residents (first_name, last_name, email, password, created_at, status) 
                    VALUES (?, ?, ?, ?, NOW(), 'active')
                ");
                
                $result = $stmt->execute([
                    $first_name,
                    $last_name, 
                    $email,
                    $hashed_password
                ]);
            }
            
            if ($result) {
                $success_message = "Registration successful! You can now sign in with your credentials.";
                // Clear form data
                $first_name = $last_name = $email = $user_type = $position = $password = $confirm_password = '';
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Barangay 468</title>
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
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            overflow: hidden;
            background: white;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .logo {
            width: 300px;
            height: 300px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo-inner {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: linear-gradient(45deg, #1565C0, #E53935);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .logo-inner::before {
            content: '';
            position: absolute;
            width: 60px;
            height: 120px;
            background: white;
            border-radius: 30px 0 0 30px;
            left: 20px;
        }

        .logo-text {
            position: absolute;
            top: 10px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logo-number {
            position: absolute;
            bottom: 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .welcome-text {
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 10px;
        }

        .welcome-subtext {
            font-size: 18px;
            opacity: 0.9;
        }

        .right-panel {
            flex: 1;
            padding: 60px 40px;
            background: white;
        }

        .form-title {
            font-size: 32px;
            font-weight: 400;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: #FAFAFA;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border-color: #4FC3F7;
            background: white;
        }

        select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .checkbox-group label {
            margin-bottom: 0;
            font-size: 14px;
            color: #666;
        }

        .terms-link {
            color: #4FC3F7;
            text-decoration: none;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4FC3F7, #29B6F6);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, #29B6F6, #0288D1);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 195, 247, 0.3);
        }

        .register-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .signin-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .signin-link a {
            color: #4FC3F7;
            text-decoration: none;
            font-weight: 600;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #ffebee;
            border: 1px solid #f44336;
            color: #f44336;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success-message {
            background: #e8f5e8;
            border: 1px solid #4caf50;
            color: #4caf50;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                margin: 10px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .left-panel,
            .right-panel {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
            </div>
            <h1 class="welcome-text">Join Us</h1>
            <p class="welcome-subtext">Create your account</p>
        </div>

        <div class="right-panel">
            <h2 class="form-title">REGISTER</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" 
                               value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" 
                               value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="user_type">User Type:</label>
                    <select id="user_type" name="user_type" required>
                        <option value="">Select User Type</option>
                        <option value="official" <?php echo (($user_type ?? '') == 'official') ? 'selected' : ''; ?>>Barangay Official</option>
                        <option value="resident" <?php echo (($user_type ?? '') == 'resident') ? 'selected' : ''; ?>>Resident</option>
                    </select>
                </div>

                <div class="form-group" id="position_group" style="display: none;">
                    <label for="position">Position:</label>
                    <select id="position" name="position">
                        <option value="">Select Position</option>
                        <option value="Barangay Captain" <?php echo (($position ?? '') == 'Barangay Captain') ? 'selected' : ''; ?>>Barangay Captain</option>
                        <option value="Barangay Councilor" <?php echo (($position ?? '') == 'Barangay Councilor') ? 'selected' : ''; ?>>Barangay Councilor</option>
                        <option value="Barangay Secretary" <?php echo (($position ?? '') == 'Barangay Secretary') ? 'selected' : ''; ?>>Barangay Secretary</option>
                        <option value="Barangay Treasurer" <?php echo (($position ?? '') == 'Barangay Treasurer') ? 'selected' : ''; ?>>Barangay Treasurer</option>
                        <option value="SK Chairman" <?php echo (($position ?? '') == 'SK Chairman') ? 'selected' : ''; ?>>SK Chairman</option>
                        <option value="Administrator" <?php echo (($position ?? '') == 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">I agree to the <a href="#" class="terms-link">Terms and Conditions</a></label>
                </div>

                <button type="submit" class="register-btn">REGISTER</button>
            </form>

            <div class="signin-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        // Handle dynamic show/hide of position field
        document.getElementById('user_type').addEventListener('change', function() {
            var positionGroup = document.getElementById('position_group');
            if (this.value === 'official') {
                positionGroup.style.display = 'block';
            } else {
                positionGroup.style.display = 'none';
            }
        });

        // Set initial visibility based on PHP variable (for form repopulation after errors)
        window.addEventListener('load', function() {
            var userType = "<?php echo isset($user_type) ? $user_type : ''; ?>";
            var positionGroup = document.getElementById('position_group');
            if (userType === 'official') {
                positionGroup.style.display = 'block';
            } else {
                positionGroup.style.display = 'none';
            }
        });
    </script>
</body>
</html>