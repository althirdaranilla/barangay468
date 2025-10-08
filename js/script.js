// Password toggle functionality
function togglePassword(inputId, openId = null, closedId = null) {
    const passwordInput = document.getElementById(inputId);
    
    // For login page (single password field)
    if (!openId && !closedId) {
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            if (eyeOpen) eyeOpen.style.display = 'block';
            if (eyeClosed) eyeClosed.style.display = 'none';
        } else {
            passwordInput.type = 'password';
            if (eyeOpen) eyeOpen.style.display = 'none';
            if (eyeClosed) eyeClosed.style.display = 'block';
        }
    } 
    // For forgot password page (multiple password fields)
    else {
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
}

// Form validation and initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded successfully');
    
    // Login form validation
    const loginForm = document.querySelector('form[action=""]'); // Login form
    if (loginForm && window.location.pathname.includes('login')) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }
    
    // Forgot password form validation
    const forgotForm = document.querySelector('form[action=""]'); // Forgot password form
    if (forgotForm && window.location.pathname.includes('forgotpassword')) {
        forgotForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value;
            const otp = document.getElementById('otp')?.value;
            const newPassword = document.getElementById('new_password')?.value;
            const confirmPassword = document.getElementById('confirm_password')?.value;
            
            // Email stage validation
            if (email && !isValidEmail(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
            
            // OTP stage validation
            if (otp && (!/^\d{6}$/.test(otp))) {
                e.preventDefault();
                alert('Please enter a valid 6-digit OTP.');
                return;
            }
            
            // Password stage validation
            if (newPassword && confirmPassword) {
                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long.');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return;
                }
            }
        });
    }
    
    // Auto-focus on first input field
    const firstInput = document.querySelector('input[type="email"], input[type="text"]');
    if (firstInput) {
        firstInput.focus();
    }
});

// Email validation helper function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// OTP input auto-tab functionality (for better UX)
function setupOtpAutoTab() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Auto-submit form when 6 digits are entered
                this.form.submit();
            }
        });
    }
}

// Initialize OTP auto-tab if on forgot password page
if (window.location.pathname.includes('forgotpassword')) {
    document.addEventListener('DOMContentLoaded', setupOtpAutoTab);
}

// Registration page functionality
function initializeRegistrationPage() {
    // Handle dynamic show/hide of position field
    const userTypeSelect = document.getElementById('user_type');
    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', function() {
            const positionGroup = document.getElementById('position_group');
            if (this.value === 'official') {
                positionGroup.style.display = 'block';
            } else {
                positionGroup.style.display = 'none';
            }
        });
    }

    // Set initial visibility based on PHP variable (for form repopulation after errors)
    const userType = "<?php echo isset($user_type) ? $user_type : ''; ?>";
    const positionGroup = document.getElementById('position_group');
    if (positionGroup && userType === 'official') {
        positionGroup.style.display = 'block';
    }

    // Real-time password validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput && confirmPasswordInput) {
        [passwordInput, confirmPasswordInput].forEach(input => {
            input.addEventListener('input', validatePasswords);
        });
    }

    // Form validation
    const registerForm = document.querySelector('form[action=""]');
    if (registerForm && window.location.pathname.includes('register')) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateRegistrationForm()) {
                e.preventDefault();
            }
        });
    }
}

// Password validation function
function validatePasswords() {
    const password = document.getElementById('password')?.value;
    const confirmPassword = document.getElementById('confirm_password')?.value;
    const confirmInput = document.getElementById('confirm_password');
    
    if (confirmInput && password && confirmPassword) {
        if (password !== confirmPassword) {
            confirmInput.style.borderColor = '#f44336';
        } else {
            confirmInput.style.borderColor = '#4caf50';
        }
    }
}

// Registration form validation
function validateRegistrationForm() {
    const firstName = document.getElementById('first_name')?.value.trim();
    const lastName = document.getElementById('last_name')?.value.trim();
    const email = document.getElementById('email')?.value.trim();
    const userType = document.getElementById('user_type')?.value;
    const position = document.getElementById('position')?.value;
    const password = document.getElementById('password')?.value;
    const confirmPassword = document.getElementById('confirm_password')?.value;
    const agreeTerms = document.getElementById('agree_terms')?.checked;

    let isValid = true;
    let errorMessage = '';

    if (!firstName) {
        isValid = false;
        errorMessage += 'First name is required.\n';
    }

    if (!lastName) {
        isValid = false;
        errorMessage += 'Last name is required.\n';
    }

    if (!email) {
        isValid = false;
        errorMessage += 'Email is required.\n';
    } else if (!isValidEmail(email)) {
        isValid = false;
        errorMessage += 'Invalid email format.\n';
    }

    if (!userType) {
        isValid = false;
        errorMessage += 'Please select a user type.\n';
    }

    if (userType === 'official' && !position) {
        isValid = false;
        errorMessage += 'Please select a position.\n';
    }

    if (!password) {
        isValid = false;
        errorMessage += 'Password is required.\n';
    } else if (password.length < 6) {
        isValid = false;
        errorMessage += 'Password must be at least 6 characters long.\n';
    }

    if (password !== confirmPassword) {
        isValid = false;
        errorMessage += 'Passwords do not match.\n';
    }

    if (!agreeTerms) {
        isValid = false;
        errorMessage += 'You must agree to the Terms and Conditions.\n';
    }

    if (!isValid && errorMessage) {
        alert('Please fix the following errors:\n\n' + errorMessage);
    }

    return isValid;
}

// Initialize registration page when DOM is loaded
if (window.location.pathname.includes('register')) {
    document.addEventListener('DOMContentLoaded', initializeRegistrationPage);
}