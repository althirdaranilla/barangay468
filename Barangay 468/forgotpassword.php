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
$stage = 'email'; // Default stage: enter email

// Function to send OTP email
function sendOtpEmail($email, $otp) {
    $subject = 'Password Reset OTP';
    $message = "Your OTP for password reset is: $otp\n\nThis OTP is valid for 10 minutes.";
    $headers = 'From: no-reply@barangay468.com' . "\r\n" .
               'Reply-To: no-reply@barangay468.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    return mail($email, $subject, $message, $headers);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (isset($_POST['email'])) {
            // Stage 1: Submit email
            $email = trim($_POST['email']);
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                // Check if email exists in admin_users or residents
                $user = null;
                $user_type = '';
                
                $stmt_admin = $pdo->prepare("SELECT id, first_name FROM admin_users WHERE email = ?");
                $stmt_admin->execute([$email]);
                $admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);
                
                if ($admin) {
                    $user = $admin;
                    $user_type = 'official';
                } else {
                    $stmt_resident = $pdo->prepare("SELECT id, first_name FROM residents WHERE email = ?");
                    $stmt_resident->execute([$email]);
                    $resident = $stmt_resident->fetch(PDO::FETCH_ASSOC);
                    
                    if ($resident) {
                        $user = $resident;
                        $user_type = 'resident';
                    }
                }
                
                if ($user) {
                    // Generate OTP
                    $otp = random_int(100000, 999999);
                    
                    // Send email
                    if (sendOtpEmail($email, $otp)) {
                        // Store in session (in production, use DB with expiration)
                        $_SESSION['reset_otp'] = $otp;
                        $_SESSION['reset_email'] = $email;
                        $_SESSION['reset_user_type'] = $user_type;
                        $_SESSION['reset_user_id'] = $user['id'];
                        $_SESSION['reset_otp_time'] = time(); // For simple expiration check
                        
                        $success = 'OTP sent to your email.';
                        $stage = 'otp';
                    } else {
                        $error = 'Failed to send OTP. Please try again.';
                    }
                } else {
                    $error = 'No account found with this email.';
                }
            }
        } elseif (isset($_POST['otp'])) {
            // Stage 2: Verify OTP
            $otp_input = trim($_POST['otp']);
            
            if (empty($otp_input)) {
                $error = 'Please enter the OTP.';
            } elseif (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_email'])) {
                $error = 'Session expired. Please start over.';
                $stage = 'email';
            } else {
                // Simple expiration: 10 minutes
                if (time() - $_SESSION['reset_otp_time'] > 600) {
                    $error = 'OTP expired. Please request a new one.';
                    unset($_SESSION['reset_otp']);
                    unset($_SESSION['reset_otp_time']);
                    $stage = 'email';
                } elseif ((int)$otp_input === $_SESSION['reset_otp']) {
                    $success = 'OTP verified.';
                    $stage = 'password';
                } else {
                    $error = 'Invalid OTP.';
                }
            }
        } elseif (isset($_POST['new_password'])) {
            // Stage 3: Reset password
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if (empty($new_password) || empty($confirm_password)) {
                $error = 'Please fill in both password fields.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'Passwords do not match.';
            } elseif (strlen($new_password) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } elseif (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_user_type'])) {
                $error = 'Session expired. Please start over.';
                $stage = 'email';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $table = ($_SESSION['reset_user_type'] === 'official') ? 'admin_users' : 'residents';
                
                $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
                $result = $stmt->execute([$hashed_password, $_SESSION['reset_email']]);
                
                if ($result) {
                    $success = 'Password reset successful. You can now login.';
                    // Clear session
                    unset($_SESSION['reset_otp']);
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_user_type']);
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['reset_otp_time']);
                    
                    header('Location: login.php?success=' . urlencode($success));
                    exit();
                } else {
                    $error = 'Failed to update password. Please try again.';
                }
            }
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
            max-width: 90%;
            min-height: 500px;
            display: flex;
        }

        .left {
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left::before {
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

        .right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form h2 {
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

        .submit-btn {
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

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(41, 128, 185, 0.3);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }

        .back-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
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
            .container {
                flex-direction: column;
                width: 100%;
                margin: 20px;
            }

            .left {
                min-height: 200px;
            }

            .right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
                <h1>Reset Password</h1>
                <p>Recover your account</p>
            </div>
        </div>

        <div class="right">
            <div class="form">
                <h2><?php echo ($stage === 'email') ? 'Forgot Password' : (($stage === 'otp') ? 'Enter OTP' : 'Reset Password'); ?></h2>
                
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
                    <?php if ($stage === 'email'): ?>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <button type="submit" class="submit-btn">Send OTP</button>
                    <?php elseif ($stage === 'otp'): ?>
                        <div class="form-group">
                            <label for="otp">OTP:</label>
                            <input type="text" id="otp" name="otp" required maxlength="6">
                        </div>
                        <button type="submit" class="submit-btn">Verify OTP</button>
                    <?php elseif ($stage === 'password'): ?>
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <div class="password-container">
                                <input type="password" id="new_password" name="new_password" required>
                                <span class="password-toggle" onclick="togglePassword('new_password', 'new_eye_open', 'new_eye_closed')">
                                    <svg id="new_eye_closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m-6.364-9.364L18 18"/>
                                    </svg>
                                    <svg id="new_eye_open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
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
                                <span class="password-toggle" onclick="togglePassword('confirm_password', 'confirm_eye_open', 'confirm_eye_closed')">
                                    <svg id="confirm_eye_closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m-6.364-9.364L18 18"/>
                                    </svg>
                                    <svg id="confirm_eye_open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Reset Password</button>
                    <?php endif; ?>
                </form>
                
                <div class="back-link">
                    <a href="login.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, openId, closedId) {
            const passwordInput = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);
            
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