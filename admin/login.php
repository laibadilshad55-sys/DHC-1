<?php
// Start session for error/success messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get error message from session
$error = $_SESSION['admin_login_error'] ?? '';
unset($_SESSION['admin_login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
        }

        /* Premium Glassmorphism Navbar */
        .premium-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(11, 31, 58, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.12);
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
            transition: 0.2s;
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
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .admin-badge {
            background: rgba(220, 38, 38, 0.2);
            border: 1px solid rgba(220, 38, 38, 0.5);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #ef4444;
        }

        /* Admin Hero Section */
        .admin-hero {
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
            background: radial-gradient(circle, rgba(220, 38, 38, 0.1) 0%, rgba(0,0,0,0) 70%);
            top: 20%;
            right: -10%;
            border-radius: 50%;
            pointer-events: none;
        }

        .admin-container {
            max-width: 1200px;
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

        /* Left Side - Admin Info */
        .admin-info {
            flex: 1;
            min-width: 280px;
            color: white;
        }

        .admin-info .badge {
            background: rgba(220, 38, 38, 0.2);
            backdrop-filter: blur(5px);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #ef4444;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .admin-info h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .admin-info h1 span {
            color: #ef4444;
        }

        .admin-info p {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
            margin-top: 1rem;
        }

        .security-features {
            margin-top: 2rem;
            list-style: none;
        }

        .security-features li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.8);
        }

        .security-features li i {
            color: #ef4444;
            font-size: 1.2rem;
        }

        /* Right Side - Admin Login Card */
        .admin-card {
            flex: 0.8;
            min-width: 380px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .admin-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a2c3e;
            margin-bottom: 0.5rem;
        }

        .admin-card .subtitle {
            color: #64748b;
            margin-bottom: 1.8rem;
            font-size: 0.9rem;
        }

        .admin-badge-icon {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            color: white;
            margin-left: 0.5rem;
            vertical-align: middle;
        }

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
            color: #ef4444;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
            transition: all 0.2s;
            font-family: inherit;
            outline: none;
        }

        .input-group input:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
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
            font-size: 1.1rem;
            color: #94a3b8;
        }

        .btn-admin-login {
            width: 100%;
            background: linear-gradient(95deg, #dc2626, #b91c1c);
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

        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .back-link a:hover {
            color: #dc2626;
        }

        .divider {
            margin: 1.5rem 0;
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
            .admin-container {
                flex-direction: column;
                text-align: center;
            }
            .admin-info {
                text-align: center;
            }
            .security-features li {
                justify-content: center;
            }
            .admin-card {
                min-width: 300px;
            }
        }
    </style>
</head>
<body>

<!-- PREMIUM NAVBAR -->
<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions</div>
        </div>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../login.php">Login</a>
            <a href="../registration.php">Register</a>
            <a href="login.php" class="admin-badge"><i class="fas fa-shield-alt"></i> Admin Portal</a>
        </div>
    </div>
</div>

<!-- ADMIN LOGIN SECTION -->
<section class="admin-hero">
    <div class="hero-bg-glow"></div>
    <div class="admin-container">
        <!-- Left Side Info -->
        <div class="admin-info" data-aos="fade-right">
            <div class="badge"><i class="fas fa-shield-haltered"></i> Restricted Access</div>
            <h1>Admin <span>Portal</span></h1>
            <p>Secure administrative dashboard for managing providers, credentials, verifications, and system settings.</p>
            <ul class="security-features">
                <li><i class="fas fa-check-circle"></i> Multi-factor authentication enabled</li>
                <li><i class="fas fa-lock"></i> IP whitelisting available</li>
                <li><i class="fas fa-chart-line"></i> Full audit logging</li>
                <li><i class="fas fa-users"></i> User & role management</li>
            </ul>
        </div>

        <!-- Right Side Admin Login Card -->
        <div class="admin-card" data-aos="fade-left">
            <h2>Administrator Access <span class="admin-badge-icon"><i class="fas fa-crown"></i> Admin</span></h2>
            <div class="subtitle">Enter your admin credentials to continue</div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Admin Email</label>
                    <input type="email" name="email" placeholder="admin@quadsolutions.com" required autocomplete="email">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
<button type="submit" name="login" class="btn-admin-login">
                    <i class="fas fa-arrow-right-to-bracket"></i> Access Dashboard
                </button>
            </form>

            <div class="divider">
                <span><i class="fas fa-shield"></i> Secure Zone</span>
            </div>

            <div class="back-link">
                <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>

            <div class="demo-hint" style="text-align: center; font-size: 0.7rem; color: #94a3b8; margin-top: 1rem;">
                <i class="fas fa-info-circle"></i> Authorized personnel only. All activities are logged.
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | Admin Portal v2.0
    </div>
</footer>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password i');
        
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
</script>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 80 });
</script>

</body>
</html>