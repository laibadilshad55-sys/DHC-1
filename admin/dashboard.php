<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Check if user has admin role
$is_admin = false;
if(isset($_SESSION['admin_id'])){
    $is_admin = true;
    $admin_name = $_SESSION['admin_name'] ?? 'Admin';
} elseif(isset($_SESSION['user_id'])){
    // Check if user is admin from database
    include '../config/db.php';
    $uid = $_SESSION['user_id'];
    $result = $conn->query("SELECT role FROM users WHERE id='$uid'");
    if($result && $result->num_rows > 0){
        $user = $result->fetch_assoc();
        if($user['role'] == 'admin'){
            $is_admin = true;
            $_SESSION['admin_id'] = $uid;
            $_SESSION['admin_name'] = $_SESSION['user_name'] ?? 'Admin';
            $admin_name = $_SESSION['admin_name'];
        }
    }
}

if(!$is_admin){
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../config/db.php';

// Get statistics
$total_users = 0;
$total_applications = 0;
$pending_applications = 0;
$approved_applications = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM users");
if($result) $total_users = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM applications");
if($result) $total_applications = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status='pending'");
if($result) $pending_applications = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status='approved'");
if($result) $approved_applications = $result->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Quad Solutions</title>
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

        /* Action Cards */
        .action-grid {
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

        /* Recent Applications Table */
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

        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-action {
            background: none;
            border: none;
            cursor: pointer;
            margin: 0 0.3rem;
            font-size: 1rem;
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
            th, td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions | Admin</div>
        </div>
        <div class="nav-links">
            <a href="dashboard.php" style="color: white;">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="applications.php">Applications</a>
            <div class="user-info">
                <i class="fas fa-user-shield"></i>
                <span><?php echo htmlspecialchars($admin_name); ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1><i class="fas fa-chart-line"></i> Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</h1>
        <p>Here's what's happening with your medical credentialing system today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <h3>Total Users</h3>
            <div class="stat-number"><?php echo $total_users; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <h3>Total Applications</h3>
            <div class="stat-number"><?php echo $total_applications; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <h3>Pending Applications</h3>
            <div class="stat-number"><?php echo $pending_applications; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3>Approved</h3>
            <div class="stat-number"><?php echo $approved_applications; ?></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="action-grid">
        <a href="users.php" class="action-card">
            <i class="fas fa-users"></i>
            <h3>Manage Users</h3>
            <p>View, edit, and manage all registered users</p>
        </a>
        <a href="applications.php" class="action-card">
            <i class="fas fa-file-alt"></i>
            <h3>View Applications</h3>
            <p>Review and process credentialing applications</p>
        </a>
        <a href="reports.php" class="action-card">
            <i class="fas fa-chart-line"></i>
            <h3>Generate Reports</h3>
            <p>View analytics and compliance reports</p>
        </a>
    </div>

    <!-- Recent Applications -->
    <div class="recent-section">
        <div class="section-title">
            <i class="fas fa-history"></i> Recent Applications
        </div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Submitted On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM applications ORDER BY id DESC LIMIT 10");
                    if($result && $result->num_rows > 0):
                        while($row = $result->fetch_assoc()):
                            $status_class = $row['status'] == 'pending' ? 'status-pending' : ($row['status'] == 'approved' ? 'status-approved' : 'status-rejected');
                    ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'] ?? 'now')); ?></td>
                        <td>
                            <button class="btn-action" title="View"><i class="fas fa-eye" style="color:#0072ff;"></i></button>
                            <button class="btn-action" title="Edit"><i class="fas fa-edit" style="color:#f59e0b;"></i></button>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No applications found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | Admin Dashboard
    </div>
</footer>

</body>
</html>