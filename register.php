<?php
session_start();

// Database connection configuration
$host = '127.0.0.1';
$dbname = 'u539413584_db';
$username = 'u539413584_admin';
$password = 'Q5b&kOh+2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    $host = "127.0.0.1";
    $dbname = "barangay468_db";
    $username = "root";
    $password = "";
    //$error = 'Database connection failed: ' . $e->getMessage();
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                SELECT id FROM residents_users WHERE email = ?
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
                    VALUES (?, ?, ?, ?, ?, NOW(), 'inactive')
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
                    INSERT INTO residents_users (first_name, last_name, email, password, created_at, status) 
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="register-logo">
                <img src="images/logo.png" alt="Logo">
            </div>
            <h1 class="welcome-text">Join Us</h1>
            <p class="welcome-subtext">Create your account</p>
        </div>

        <div class="register-right">
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
                        <div class="password-container">
                            <input type="password" id="password" name="password" required>
                            <span class="password-toggle" onclick="togglePassword('password', 'eye-open', 'eye-closed')">
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m-6.364-9.364L18 18"/>
                                </svg>
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <div class="password-container">
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <span class="password-toggle" onclick="togglePassword('confirm_password', 'eye-open-confirm', 'eye-closed-confirm')">
                                <svg id="eye-closed-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m-6.364-9.364L18 18"/>
                                </svg>
                                <svg id="eye-open-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">I agree to the <a href="#" class="terms-link">Terms and Conditions</a></label>
                </div>

                <button type="submit" class="register-btn">REGISTER</button>
            </form>

            <div class="signin-link">
                Already have an account? <a href="index.php">Sign In</a>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>