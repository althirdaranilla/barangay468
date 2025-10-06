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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-left">
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
                <h1>Reset Password</h1>
                <p>Recover your account</p>
            </div>
        </div>

        <div class="forgot-right">
            <div class="forgot-form">
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
                            <input type="text" id="otp" name="otp" required maxlength="6" pattern="[0-9]{6}" title="Please enter a 6-digit OTP">
                        </div>
                        <button type="submit" class="submit-btn">Verify OTP</button>
                    <?php elseif ($stage === 'password'): ?>
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <div class="password-container">
                                <input type="password" id="new_password" name="new_password" required minlength="6">
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
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
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

    <script src="js/script.js"></script>
</body>
</html>