<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// Fetch user data
$result = $conn->query("SELECT * FROM users WHERE id = '$uid'");
$user = $result->fetch_assoc();

// Update profile
if(isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    
    // Check if email already exists for another user
    $check = $conn->query("SELECT id FROM users WHERE email='$email' AND id!='$uid'");
    if($check->num_rows > 0){
        $error_msg = "Email already exists!";
    } else {
        $update = "UPDATE users SET name='$name', email='$email', phone='$phone', specialty='$specialty' WHERE id='$uid'";
        
        if($conn->query($update)){
            $success_msg = "Profile updated successfully!";
            // Refresh user data
            $result = $conn->query("SELECT * FROM users WHERE id = '$uid'");
            $user = $result->fetch_assoc();
            $_SESSION['user_name'] = $name;
        } else {
            $error_msg = "Error updating profile: " . $conn->error;
        }
    }
}

// Update password
if(isset($_POST['update_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    if(password_verify($current_password, $user['password'])){
        if($new_password == $confirm_password){
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update = "UPDATE users SET password='$hashed_password' WHERE id='$uid'";
            if($conn->query($update)){
                $success_msg = "Password updated successfully!";
            } else {
                $error_msg = "Error updating password.";
            }
        } else {
            $error_msg = "New passwords do not match!";
        }
    } else {
        $error_msg = "Current password is incorrect!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Quad Solutions</title>
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

        /* Premium Navbar */
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            background: rgba(255,255,255,0.1);
            padding: 0.4rem 1rem;
            border-radius: 40px;
        }

        .logout-btn {
            background: rgba(220, 38, 38, 0.8);
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            text-decoration: none;
            color: white;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* Main Content */
        .main-content {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, #0b1f3a, #1a3a4a);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .profile-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #dc2626;
        }

        /* Form Sections */
        .form-section {
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .section-title i {
            color: #0072ff;
            margin-right: 8px;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
        }

        .form-group label i {
            color: #0072ff;
            margin-right: 6px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #00c6ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,198,255,0.1);
        }

        .form-group input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(95deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,114,255,0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            color: #475569;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            border-color: #0072ff;
            color: #0072ff;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        /* Role Badge */
        .role-badge {
            display: inline-block;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0369a1;
        }

        /* Footer */
        .footer-premium {
            background: #071626;
            padding: 2rem;
            text-align: center;
            color: #adc4dc;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 0.5rem;
            }
            .form-section {
                padding: 1.5rem;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .button-group {
                flex-direction: column;
            }
            .btn-primary, .btn-outline {
                width: 100%;
                text-align: center;
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
            <a href="dashboard.php">Dashboard</a>
            <a href="apply.php">Apply</a>
            <a href="status.php">Status</a>
            <a href="profile.php" style="color: white;">Profile</a>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['user_name'] ?? $user['name'] ?? 'User'; ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="profile-card">
        
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-md"></i>
            </div>
            <h1>My Profile</h1>
            <p>Manage your personal and professional information</p>
        </div>
        
        <div class="form-section">
            <!-- Success/Error Messages -->
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
            
            <!-- Update Profile Form -->
            <div class="section-title">
                <i class="fas fa-user-edit"></i> Personal Information
            </div>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+92 321 567 8900">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-stethoscope"></i> Specialty</label>
                        <select name="specialty">
                            <option value="">Select Specialty</option>
                            <option value="Cardiology" <?php echo ($user['specialty'] ?? '') == 'Cardiology' ? 'selected' : ''; ?>>Cardiology</option>
                            <option value="Dermatology" <?php echo ($user['specialty'] ?? '') == 'Dermatology' ? 'selected' : ''; ?>>Dermatology</option>
                            <option value="Emergency Medicine" <?php echo ($user['specialty'] ?? '') == 'Emergency Medicine' ? 'selected' : ''; ?>>Emergency Medicine</option>
                            <option value="Family Medicine" <?php echo ($user['specialty'] ?? '') == 'Family Medicine' ? 'selected' : ''; ?>>Family Medicine</option>
                            <option value="Internal Medicine" <?php echo ($user['specialty'] ?? '') == 'Internal Medicine' ? 'selected' : ''; ?>>Internal Medicine</option>
                            <option value="Neurology" <?php echo ($user['specialty'] ?? '') == 'Neurology' ? 'selected' : ''; ?>>Neurology</option>
                            <option value="Pediatrics" <?php echo ($user['specialty'] ?? '') == 'Pediatrics' ? 'selected' : ''; ?>>Pediatrics</option>
                            <option value="Psychiatry" <?php echo ($user['specialty'] ?? '') == 'Psychiatry' ? 'selected' : ''; ?>>Psychiatry</option>
                            <option value="Surgery" <?php echo ($user['specialty'] ?? '') == 'Surgery' ? 'selected' : ''; ?>>Surgery</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Role</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['role'] ?? 'user'); ?>" disabled>
                    <small style="color: #64748b; font-size: 0.7rem;">Role cannot be changed</small>
                </div>
                <div class="button-group">
                    <button type="submit" name="update_profile" class="btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                    <a href="dashboard.php" class="btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Change Password Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-lock"></i> Change Password
            </div>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-key"></i> Current Password</label>
                    <input type="password" name="current_password" placeholder="Enter current password" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" name="new_password" placeholder="Enter new password" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" name="update_password" class="btn-primary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<!-- Footer -->
<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | My Profile
    </div>
</footer>

</body>
</html>