<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // 1 for debugging, 0 for production

// Check if user is logged in
if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Determine user type and name
$is_admin = isset($_SESSION['admin_id']);
$user_id = $is_admin ? $_SESSION['admin_id'] : $_SESSION['user_id'];
$user_name = $is_admin ? ($_SESSION['admin_name'] ?? 'Admin') : ($_SESSION['user_name'] ?? 'User');
$user_role = $is_admin ? 'Administrator' : 'Healthcare Provider';

// Include database connection
require_once(__DIR__ . "/config/db.php");

// Get dashboard statistics
$total_applications = 0;
$pending_credentials = 0;
$approved_credentials = 0;

if (!$is_admin) {
    // User-specific stats

    // Total applications
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);
    $total_applications = $row['count'];

    // Pending
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE user_id = $user_id AND status='pending'");
    $row = mysqli_fetch_assoc($result);
    $pending_credentials = $row['count'];

    // Approved
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE user_id = $user_id AND status='approved'");
    $row = mysqli_fetch_assoc($result);
    $approved_credentials = $row['count'];

} else {
    // Admin stats

    // Total users
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $row = mysqli_fetch_assoc($result);
    $total_applications = $row['count'];

    // Pending applications
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE status='pending'");
    $row = mysqli_fetch_assoc($result);
    $pending_credentials = $row['count'];

    // Approved applications
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE status='approved'");
    $row = mysqli_fetch_assoc($result);
    $approved_credentials = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Quad Solutions</title>
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
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(120deg, #FFFFFF, #B0E0FF);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        .nav-links a:hover {
            color: white;
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

        .user-info i {
            color: #00c6ff;
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

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #0b1f3a, #1a3a4a);
            border-radius: 1.5rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .role-badge {
            display: inline-block;
            background: rgba(0,198,255,0.2);
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin: 0 auto 1rem;
        }

        .stat-card h3 {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #1a2c3e;
        }

        /* Quick Actions Grid */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.8rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .action-card i {
            font-size: 2.5rem;
            color: #0072ff;
            margin-bottom: 1rem;
        }

        .action-card h3 {
            margin-bottom: 0.5rem;
            color: #1a2c3e;
        }

        .action-card p {
            color: #64748b;
            font-size: 0.85rem;
        }

        /* Recent Activity */
        .recent-section {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
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
            .dashboard-container {
                padding: 0 1rem;
            }
            .welcome-section h1 {
                font-size: 1.3rem;
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
            <?php if(!$is_admin): ?>
            <a href="apply.php">Apply</a>
            <a href="status.php">Status</a>
            <?php else: ?>
            <a href="admin/users.php">Users</a>
            <a href="admin/credentials.php">Credentials</a>
            <?php endif; ?>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo htmlspecialchars($user_name); ?></span>
                <span style="font-size:0.7rem; opacity:0.7;">(<?php echo $user_role; ?>)</span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="dashboard-container">
    
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1><i class="fas fa-chart-line"></i> Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Manage your medical credentials and track verification status from one dashboard.</p>
        <div class="role-badge">
            <i class="fas <?php echo $is_admin ? 'fa-shield-alt' : 'fa-user-md'; ?>"></i>
            <?php echo $user_role; ?> Access
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <h3><?php echo $is_admin ? 'Total Users' : 'My Applications'; ?></h3>
            <div class="stat-number"><?php echo $total_applications; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <h3>Pending Credentials</h3>
            <div class="stat-number"><?php echo $pending_credentials; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3>Approved</h3>
            <div class="stat-number"><?php echo $approved_credentials; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Security Level</h3>
            <div class="stat-number">High</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="actions-grid">
        <?php if(!$is_admin): ?>
        <a href="apply.php" class="action-card">
            <i class="fas fa-pen-alt"></i>
            <h3>Apply for Credentialing</h3>
            <p>Submit new application for medical credential verification</p>
        </a>
        <a href="status.php" class="action-card">
            <i class="fas fa-chart-simple"></i>
            <h3>Check Status</h3>
            <p>Track your application and credential status</p>
        </a>
        <a href="profile.php" class="action-card">
            <i class="fas fa-user-edit"></i>
            <h3>Update Profile</h3>
            <p>Manage your personal and professional information</p>
        </a>
        <?php else: ?>
        <a href="admin/users.php" class="action-card">
            <i class="fas fa-users"></i>
            <h3>Manage Users</h3>
            <p>View, edit, and manage all registered users</p>
        </a>
        <a href="admin/credentials.php" class="action-card">
            <i class="fas fa-id-card"></i>
            <h3>Verify Credentials</h3>
            <p>Review and approve provider credentials</p>
        </a>
        <a href="admin/reports.php" class="action-card">
            <i class="fas fa-chart-line"></i>
            <h3>View Reports</h3>
            <p>Generate compliance and activity reports</p>
        </a>
        <?php endif; ?>
    </div>

    <!-- Recent Activity Section -->
    <div class="recent-section">
        <div class="section-title">
            <i class="fas fa-history"></i> Recent Activity
        </div>
        <p style="color: #64748b; text-align: center; padding: 2rem;">
            <i class="fas fa-info-circle"></i> Your recent activities will appear here
        </p>
    </div>
</div>

<!-- Footer -->
<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | Secure Dashboard
    </div>
</footer>

</body>
</html>