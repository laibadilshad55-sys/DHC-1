<?php
session_start();
include 'config/db.php';

$error_msg = '';
$success_msg = '';
$token = $_GET['token'] ?? '';

// Verify token
if($token){
    $result = $conn->query("SELECT * FROM password_resets WHERE token='$token' AND expires_at > NOW() AND used=0");
    if($result->num_rows == 0){
        $error_msg = "Invalid or expired reset link. Please request a new one.";
        $token = '';
    }
}

// Process password reset
if(isset($_POST['reset_password']) && $token){
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password == $confirm_password){
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Get email from token
        $reset = $conn->query("SELECT email FROM password_resets WHERE token='$token'");
        $email = $reset->fetch_assoc()['email'];
        
        // Update password
        $conn->query("UPDATE users SET password='$hashed_password' WHERE email='$email'");
        
        // Mark token as used
        $conn->query("UPDATE password_resets SET used=1 WHERE token='$token'");
        
        $success_msg = "Password reset successfully! You can now login with your new password.";
    } else {
        $error_msg = "Passwords do not match!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; }
        .premium-nav { background: rgba(11, 31, 58, 0.95); backdrop-filter: blur(14px); padding: 1rem 2.5rem; }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .logo-icon { background: linear-gradient(135deg, #00c6ff, #0072ff); width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: white; }
        .logo-text { font-size: 1.7rem; font-weight: 800; background: linear-gradient(120deg, #FFFFFF, #B0E0FF); background-clip: text; -webkit-background-clip: text; color: transparent; }
        .main-content { min-height: calc(100vh - 140px); display: flex; justify-content: center; align-items: center; padding: 3rem 2rem; background: radial-gradient(circle at 10% 30%, #0A1A2F, #05121E); }
        .premium-card { width: 500px; max-width: 90%; background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); border-radius: 2rem; padding: 2.5rem; text-align: center; border: 1px solid rgba(255,255,255,0.15); }
        .reset-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #00c6ff, #0072ff); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem; color: white; }
        .premium-card h2 { font-size: 1.8rem; margin-bottom: 0.5rem; color: white; }
        .premium-card .subtitle { font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-bottom: 1.8rem; }
        .alert { padding: 1rem; border-radius: 16px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: rgba(34,197,94,0.2); color: #4ade80; border-left: 4px solid #22c55e; }
        .alert-error { background: rgba(220,38,38,0.2); color: #fecaca; border-left: 4px solid #dc2626; }
        .form-group { margin-bottom: 1.2rem; text-align: left; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-size: 0.85rem; }
        .form-group input { width: 100%; padding: 14px 16px; border: 1.5px solid rgba(255,255,255,0.2); border-radius: 14px; background: rgba(255,255,255,0.1); color: white; outline: none; }
        .form-group input:focus { border-color: #00c6ff; }
        .btn-reset { width: 100%; background: linear-gradient(95deg, #00c6ff, #0072ff); border: none; padding: 14px; border-radius: 40px; font-weight: 700; color: white; cursor: pointer; }
        .btn-reset:hover { transform: translateY(-2px); }
        .back-link { margin-top: 1.5rem; }
        .back-link a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .back-link a:hover { color: #00c6ff; }
        .footer-premium { background: #071626; padding: 1.5rem; text-align: center; color: #adc4dc; }
        @media (max-width: 768px) { .premium-card { padding: 1.5rem; } }
    </style>
</head>
<body>
<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area" style="display:flex; align-items:center; gap:12px;">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions</div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="premium-card">
        <div class="reset-icon"><i class="fas fa-lock-open"></i></div>
        <h2>Reset Password</h2>
        <div class="subtitle">Create a new password for your account</div>
        
        <?php if($success_msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i><div><?php echo $success_msg; ?></div></div>
        <div class="back-link"><a href="login.php"><i class="fas fa-arrow-left"></i> Go to Login</a></div>
        <?php elseif($error_msg): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i><div><?php echo $error_msg; ?></div></div>
        <div class="back-link"><a href="forgot.php"><i class="fas fa-arrow-left"></i> Request New Reset Link</a></div>
        <?php elseif($token): ?>
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-lock"></i> New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" name="reset_password" class="btn-reset"><i class="fas fa-save"></i> Reset Password</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<footer class="footer-premium">© 2026 Quad Solutions | Medical Credentialing System</footer>
</body>
</html>