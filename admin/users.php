<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Get all users
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; }
        
        /* Sidebar */
        .sidebar { width: 280px; height: 100vh; background: linear-gradient(180deg, #0b1f3a 0%, #0a1525 100%); position: fixed; left: 0; top: 0; padding: 2rem 1.5rem; }
        .sidebar h2 { color: white; font-size: 1.3rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar h2 i { color: #00c6ff; margin-right: 10px; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.7); text-decoration: none; padding: 0.8rem 1rem; margin: 0.5rem 0; border-radius: 12px; transition: all 0.3s; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar a.active { background: rgba(0,198,255,0.2); color: #00c6ff; }
        .logout-btn { margin-top: 2rem; background: rgba(220,38,38,0.2); color: #ef4444 !important; }
        .logout-btn:hover { background: #dc2626 !important; color: white !important; }
        
        /* Main Content */
        .main { margin-left: 280px; padding: 2rem; }
        .header { background: white; padding: 1.5rem 2rem; border-radius: 1.5rem; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header h1 { font-size: 1.5rem; color: #1a2c3e; margin-bottom: 0.3rem; }
        .header p { color: #64748b; }
        
        /* Table */
        .table-container { background: white; border-radius: 1.5rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-weight: 600; color: #475569; }
        .role-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
        .role-admin { background: #fee2e2; color: #b91c1c; }
        .role-user { background: #dcfce7; color: #166534; }
        .footer { text-align: center; padding: 1.5rem; color: #94a3b8; font-size: 0.85rem; margin-top: 2rem; }
        
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main { margin-left: 0; } }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fas fa-shield-alt"></i> Quad Solutions</h2>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="users.php" class="active"><i class="fas fa-users"></i> Users</a>
    <a href="applications.php"><i class="fas fa-file-alt"></i> Applications</a>
    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
    <div class="header">
        <h1><i class="fas fa-users"></i> Manage Users</h1>
        <p>View and manage all registered users</p>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Registered</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><span class="role-badge <?php echo $row['role'] == 'admin' ? 'role-admin' : 'role-user'; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                    <td><?php echo ucfirst($row['status'] ?? 'Active'); ?></td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'] ?? 'now')); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <div class="footer">© 2026 Quad Solutions | Medical Credentialing System</div>
</div>

</body>
</html>