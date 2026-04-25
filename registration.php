<?php
// Start session for error/success messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get error/success message from session
$error = $_SESSION['register_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_error'], $_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Quad Solutions</title>
    <!-- Google Fonts + Icons + AOS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1A2C3E;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        /* Premium Glassmorphism Navbar - EXACT same as index & login */
        .premium-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(11, 31, 58, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2.5rem;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(0,114,255,0.3);
        }

        .logo-text {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(120deg, #FFFFFF, #B0E0FF);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: 0.2s;
            letter-spacing: -0.2px;
            position: relative;
        }

        .nav-links a:hover {
            color: white;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            transition: 0.3s;
            border-radius: 2px;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-premium-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.4);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            transition: 0.2s;
        }

        .btn-premium-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #00c6ff;
        }

        /* Registration Hero Section */
        .register-hero {
            min-height: calc(100vh - 80px);
            background: radial-gradient(circle at 10% 30%, #0A1A2F, #05121E);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-glow {
            position: absolute;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(0,198,255,0.1) 0%, rgba(0,0,0,0) 70%);
            top: 20%;
            right: -10%;
            border-radius: 50%;
            pointer-events: none;
        }

        .register-container {
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        /* Left Side - Info Panel */
        .register-info {
            flex: 1;
            min-width: 280px;
            color: white;
        }

        .register-info .badge {
            background: rgba(0,198,255,0.2);
            backdrop-filter: blur(5px);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #00e0ff;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,198,255,0.3);
        }

        .register-info h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .register-info h1 span {
            color: #00c6ff;
        }

        .register-info p {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
            margin-top: 1rem;
        }

        .benefits-list {
            margin-top: 2rem;
            list-style: none;
        }

        .benefits-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.8);
        }

        .benefits-list li i {
            color: #28a745;
            font-size: 1.2rem;
        }

        /* Right Side - Registration Card Premium */
        .register-card {
            flex: 0.9;
            min-width: 420px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .register-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a2c3e;
            margin-bottom: 0.5rem;
        }

        .register-card .subtitle {
            color: #64748b;
            margin-bottom: 1.8rem;
            font-size: 0.9rem;
        }

        /* Form Styles */
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 0;
        }

        .form-row .input-group {
            flex: 1;
        }

        .input-group {
            margin-bottom: 1.2rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #334155;
            font-size: 0.85rem;
        }

        .input-group label i {
            margin-right: 6px;
            color: #00c6ff;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: inherit;
            outline: none;
            background: #fefefe;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #00c6ff;
            box-shadow: 0 0 0 3px rgba(0, 198, 255, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: transparent;
            border: none;
            font-size: 1rem;
            color: #94a3b8;
        }

        /* Alert Messages */
        .alert {
            padding: 0.9rem 1rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(95deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 114, 255, 0.4);
        }

        .terms {
            margin-top: 1.2rem;
            font-size: 0.75rem;
            text-align: center;
            color: #64748b;
        }

        .terms a {
            color: #0072ff;
            text-decoration: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #475569;
        }

        .login-link a {
            color: #0072ff;
            font-weight: 600;
            text-decoration: none;
        }

        .divider {
            margin: 1.5rem 0 1rem;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Footer */
        .footer-premium {
            background: #071626;
            padding: 2rem 2rem 1.5rem;
            color: #adc4dc;
            text-align: center;
        }

        .copyright {
            font-size: 0.85rem;
        }

        @media (max-width: 900px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            .register-container {
                flex-direction: column;
                text-align: center;
            }
            .register-info {
                text-align: center;
            }
            .benefits-list li {
                justify-content: center;
            }
            .register-card {
                min-width: 320px;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>

<!-- PREMIUM NAVBAR - EXACT same as index.php and login.php -->
<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions</div>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="registration.php" style="color: white;">Register</a>
            <a href="admin/login.php" class="btn-premium-outline">Admin Panel</a>
        </div>
    </div>
</div>

<!-- REGISTRATION SECTION - Premium Style -->
<section class="register-hero">
    <div class="hero-bg-glow"></div>
    <div class="register-container">
        <!-- Left Side Info -->
        <div class="register-info" data-aos="fade-right">
            <div class="badge"><i class="fas fa-user-md"></i> Join Quad Solutions Today</div>
            <h1>Create <span>Account</span></h1>
            <p>Register as a healthcare provider and get access to our comprehensive credentialing management platform.</p>
            <ul class="benefits-list">
                <li><i class="fas fa-check-circle"></i> Manage licenses & certifications</li>
                <li><i class="fas fa-bell"></i> Renewal reminders & alerts</li>
                <li><i class="fas fa-chart-simple"></i> Track verification status</li>
                <li><i class="fas fa-headset"></i> 24/7 priority support</li>
            </ul>
        </div>

        <!-- Right Side Registration Card -->
        <div class="register-card" data-aos="fade-left">
            <h2>Register</h2>
            <div class="subtitle">Fill in your details to get started</div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="register_process.php" method="POST" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-row">
                    <div class="input-group">
                        <label><i class="fas fa-user"></i> First Name</label>
                        <input type="text" name="first_name" placeholder="Ali" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-user"></i> Last Name</label>
                        <input type="text" name="last_name" placeholder="Ahmad" required>
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="doctor@hospital.com" required autocomplete="email">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" name="phone" placeholder="+92 334 567 8900">
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-stethoscope"></i> Specialty (Optional)</label>
                    <select name="specialty">
                        <option value="">Select Specialty</option>
                        <option>Cardiology</option>
                        <option>Dermatology</option>
                        <option>Emergency Medicine</option>
                        <option>Family Medicine</option>
                        <option>Internal Medicine</option>
                        <option>Neurology</option>
                        <option>Pediatrics</option>
                        <option>Psychiatry</option>
                        <option>Surgery</option>
                        <option>Other</option>
                    </select>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Register Account
                </button>

                <div class="terms">
                    By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                </div>

                <div class="divider">
                    <span>Already have an account?</span>
                </div>

                <div class="login-link">
                    <a href="login.php"><i class="fas fa-arrow-right-to-bracket"></i> Sign in here</a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FOOTER - Same as index and login -->
<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | HIPAA Compliant Framework
    </div>
</footer>

<script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = passwordInput.parentElement.querySelector('.toggle-password i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }

    // Password match validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long!');
            return false;
        }
    });
</script>

<!-- AOS Script for animations -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 80
    });
</script>

</body>
</html>