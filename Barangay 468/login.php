<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'barangay468_db';
$username = 'root';
$password = ''; // root has no password

// Initialize variables
$error = '';
$success = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'official') {
        header('Location: admin/Dashboard.php');
    } else {
        header('Location: residents/Dashboard.php');
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
                
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60); // 30 days
                    setcookie('remember_token', $token, $expires, '/', '', false, true);
                }
                
                header('Location: admin/Dashboard.php');
                exit();
            }
            
            // If not found in admin, check residents
            $stmt_resident = $pdo->prepare("SELECT id, email, password, first_name, last_name FROM residents WHERE email = ?");
            $stmt_resident->execute([$email]);
            $user_resident = $stmt_resident->fetch(PDO::FETCH_ASSOC);
            
            if ($user_resident && password_verify($password_input, $user_resident['password'])) {
                $_SESSION['user_type'] = 'resident';
                $_SESSION['user_id'] = $user_resident['id'];
                $_SESSION['user_email'] = $user_resident['email'];
                $_SESSION['user_name'] = $user_resident['first_name'] . ' ' . $user_resident['last_name'];
                
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60); // 30 days
                    setcookie('remember_token', $token, $expires, '/', '', false, true);
                }
                
                header('Location: residents/Dashboard.php');
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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
            max-width: 90%;
            min-height: 500px;
            display: flex;
        }

        .login-left {
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-100px); }
        }

        .logo {
            text-align: center;
            z-index: 1;
        }

        .logo img {
            width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .logo h1 {
            color: white;
            font-size: 2.5em;
            font-weight: 300;
            margin-bottom: 10px;
        }

        .logo p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1em;
        }

        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 40px;
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2980b9;
            background: white;
            box-shadow: 0 0 0 3px rgba(41, 128, 185, 0.1);
        }

        /* Password field container */
        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            padding: 5px;
            color: #666;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #2980b9;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: auto;
        }

        .forgot-password {
            color: #2980b9;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(41, 128, 185, 0.3);
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }

        .signup-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 100%;
                margin: 20px;
            }

            .login-left {
                min-height: 200px;
            }

            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
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

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            } else {
                passwordInput.type = 'password';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            }
        }
    </script>
</body>
</html>