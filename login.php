<?php
session_start();

// Database configuration
$host = "127.0.0.1";
$dbname = "barangay468_db";
$username = "root";
$password = "";
// Initialize variables
$error = '';
$success = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'official') {
        header('Location: admin/Dashboard.php');
    } else {
        header('Location: resident/Dashboard.php');
    }
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password_input)) {
        $error = 'Please fill in all fields.';
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check admin_users first
            $stmt_admin = $pdo->prepare("SELECT id, email, password, first_name, last_name, position FROM admin_users WHERE email = ?");
            $stmt_admin->execute([$email]);
            $user_admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);
            
            if ($user_admin && password_verify($password_input, $user_admin['password'])) {
                $_SESSION['user_type'] = 'official';
                $_SESSION['user_id'] = $user_admin['id'];
                $_SESSION['user_email'] = $user_admin['email'];
                $_SESSION['user_name'] = $user_admin['first_name'] . ' ' . $user_admin['last_name'];
                $_SESSION['user_position'] = $user_admin['position'];
                $_SESSION['is_admin'] = True;
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60); // 30 days
                    setcookie('remember_token', $token, $expires, '/', '', false, true);
                }
                
                header('Location: admin/Dashboard.php');
                exit();
            }
            
            // If not found in admin, check residents
            $stmt_resident = $pdo->prepare("SELECT id, email, password, first_name, last_name FROM residents_users WHERE email = ?");
            $stmt_resident->execute([$email]);
            $user_resident = $stmt_resident->fetch(PDO::FETCH_ASSOC);
            
            if ($user_resident && password_verify($password_input, $user_resident['password'])) {
                $_SESSION['user_type'] = 'resident';
                $_SESSION['user_id'] = $user_resident['id'];
                $_SESSION['user_email'] = $user_resident['email'];
                $_SESSION['user_name'] = $user_resident['first_name'] . ' ' . $user_resident['last_name'];
                $_SESSION['is_admin'] = False;
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60); // 30 days
                    setcookie('remember_token', $token, $expires, '/', '', false, true);
                }
                
                header('Location: resident/Dashboard.php');
                exit();
            }
            
            $error = 'Invalid email or password.';
            
        } catch (PDOException $e) {
            $error = 'Database connection failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
                <h1>Welcome</h1>
                <p>Sign in to your account</p>
            </div>
        </div>

        <div class="login-right">
            <div class="login-form">
                <h2>LOGIN</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" required>
                            <span class="password-toggle" onclick="togglePassword()">
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
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">LOGIN</button>
                </form>
                
                <div class="signup-link">
                    Don't you have an account? <a href="register.php">Sign up</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>