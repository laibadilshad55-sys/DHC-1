<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_apps = $conn->query("SELECT COUNT(*) as count FROM applications")->fetch_assoc()['count'];
$pending_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status='pending'")->fetch_assoc()['count'];
$approved_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status='approved'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; }
        
        .sidebar { width: 280px; height: 100vh; background: linear-gradient(180deg, #0b1f3a 0%, #0a1525 100%); position: fixed; left: 0; top: 0; padding: 2rem 1.5rem; }
        .sidebar h2 { color: white; font-size: 1.3rem; margin-bottom: 2rem; }
        .sidebar h2 i { color: #00c6ff; margin-right: 10px; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.7); text-decoration: none; padding: 0.8rem 1rem; margin: 0.5rem 0; border-radius: 12px; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar a.active { background: rgba(0,198,255,0.2); color: #00c6ff; }
        .logout-btn { margin-top: 2rem; background: rgba(220,38,38,0.2); color: #ef4444 !important; }
        
        .main { margin-left: 280px; padding: 2rem; }
        .header { background: white; padding: 1.5rem 2rem; border-radius: 1.5rem; margin-bottom: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 1rem; text-align: center; }
        .stat-card h3 { font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem; }
        .stat-number { font-size: 2rem; font-weight: 800; color: #1a2c3e; }
        .footer { text-align: center; padding: 1.5rem; color: #94a3b8; margin-top: 2rem; }
        
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main { margin-left: 0; } }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fas fa-shield-alt"></i> Quad Solutions</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="users.php"><i class="fas fa-users"></i> Users</a>
    <a href="applications.php"><i class="fas fa-file-alt"></i> Applications</a>
    <a href="reports.php" class="active"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
    <div class="header">
        <h1><i class="fas fa-chart-line"></i> System Reports</h1>
        <p>View analytics and statistics</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card"><h3>Total Users</h3><div class="stat-number"><?php echo $total_users; ?></div></div>
        <div class="stat-card"><h3>Total Applications</h3><div class="stat-number"><?php echo $total_apps; ?></div></div>
        <div class="stat-card"><h3>Pending Reviews</h3><div class="stat-number"><?php echo $pending_apps; ?></div></div>
        <div class="stat-card"><h3>Approved</h3><div class="stat-number"><?php echo $approved_apps; ?></div></div>
    </div>
    
    <div class="footer">© 2026 Quad Solutions | Medical Credentialing System</div>
</div>

</body>
</html>