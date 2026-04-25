<?php
session_start();
include 'config/db.php';

$success_msg = '';
$error_msg = '';

// Process forgot password request
if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    // Check if email exists
    $result = $conn->query("SELECT id, name, email FROM users WHERE email='$email'");
    
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save token in database (create password_resets table if not exists)
        $conn->query("INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expiry')");
        
        // Create reset link
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/DHC-1/reset_password.php?token=" . $token;
        
        // Send email (using mail() function - configure SMTP for production)
        $subject = "Password Reset Request - Quad Solutions";
        $message = "Hello " . $user['name'] . ",\n\n";
        $message .= "We received a request to reset your password.\n\n";
        $message .= "Click the link below to reset your password:\n";
        $message .= $reset_link . "\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you didn't request this, please ignore this email.\n\n";
        $message .= "Best regards,\nQuad Solutions Team";
        $headers = "From: noreply@quadsolutions.com\r\n";
        
        if(mail($email, $subject, $message, $headers)){
            $success_msg = "Password reset link has been sent to your email address.";
        } else {
            // For localhost development, show the link
            $success_msg = "Reset link generated! <br> <a href='$reset_link' style='color:#0072ff;'>Click here to reset password</a> (Email sending failed on localhost)";
        }
    } else {
        $error_msg = "Email address not found in our records.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }

        /* Premium Navbar - Same as index */
        .premium-nav {
            background: rgba(11, 31, 58, 0.95);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2.5rem;
            flex-wrap: wrap;
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
            flex-wrap: wrap;
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

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 140px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            background: radial-gradient(circle at 10% 30%, #0A1A2F, #05121E);
            position: relative;
        }

        .bg-glow {
            position: absolute;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(0,198,255,0.1) 0%, rgba(0,0,0,0) 70%);
            top: 20%;
            right: -10%;
            border-radius: 50%;
            pointer-events: none;
        }

        /* Premium Card */
        .premium-card {
            width: 500px;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 2.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .forgot-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 25px rgba(0,114,255,0.3);
        }

        .premium-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .premium-card .subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.8rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.2);
            color: #fecaca;
            border-left: 4px solid #dc2626;
        }

        .alert i {
            font-size: 1.2rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        .form-group label i {
            margin-right: 6px;
            color: #00c6ff;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            font-size: 1rem;
            transition: all 0.2s;
            font-family: inherit;
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-wrapper input:focus {
            border-color: #00c6ff;
            box-shadow: 0 0 0 3px rgba(0, 198, 255, 0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        /* Button */
        .btn-reset {
            width: 100%;
            background: linear-gradient(95deg, #00c6ff, #0072ff);
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 114, 255, 0.4);
        }

        /* Links */
        .back-link {
            margin-top: 1.5rem;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.2s;
        }

        .back-link a:hover {
            color: #00c6ff;
        }

        .back-link i {
            margin-right: 6px;
        }

        /* Info Text */
        .info-text {
            margin-top: 1.5rem;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Footer */
        .footer-premium {
            background: #071626;
            padding: 1.5rem;
            text-align: center;
            color: #adc4dc;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 0.5rem;
            }
            .premium-card {
                padding: 1.8rem;
            }
        }
    </style>
</head>
<body>

<!-- Premium Navbar -->
<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions</div>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="registration.php">Register</a>
            <a href="admin/login.php" class="admin-badge" style="background:rgba(220,38,38,0.2); padding:0.3rem 1rem; border-radius:40px; color:#ef4444;">Admin</a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="bg-glow"></div>
    
    <div class="premium-card">
        <div class="forgot-icon">
            <i class="fas fa-key"></i>
        </div>
        
        <h2>Forgot Password?</h2>
        <div class="subtitle">Enter your email to reset your password</div>
        
        <?php if($success_msg): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div><?php echo $success_msg; ?></div>
        </div>
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <div><?php echo $error_msg; ?></div>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="doctor@hospital.com" required autocomplete="email">
                </div>
            </div>
            
            <button type="submit" name="submit" class="btn-reset">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>
        
        <div class="back-link">
            <a href="login.php">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
        
        <div class="info-text">
            <i class="fas fa-info-circle"></i> We'll send a password reset link to your registered email address.
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | Password Recovery
    </div>
</footer>

</body>
</html>