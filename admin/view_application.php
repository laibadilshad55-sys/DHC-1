<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Get application ID from URL
$app_id = $_GET['id'] ?? 0;

// Fetch application details
$result = $conn->query("SELECT * FROM applications WHERE id = '$app_id'");
$application = $result->fetch_assoc();

// Fetch user details
$user_result = $conn->query("SELECT * FROM users WHERE id = '{$application['user_id']}'");
$user = $user_result->fetch_assoc();

// Fetch documents
$docs_result = $conn->query("SELECT * FROM documents WHERE application_id = '$app_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; padding: 2rem; }
        
        .container { max-width: 1000px; margin: 0 auto; }
        
        .card { background: white; border-radius: 1.5rem; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        .card h2 { font-size: 1.3rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; }
        .card h2 i { color: #0072ff; margin-right: 10px; }
        
        .info-row { display: flex; padding: 0.8rem 0; border-bottom: 1px solid #e2e8f0; }
        .info-label { width: 150px; font-weight: 600; color: #475569; }
        .info-value { flex: 1; color: #1e293b; }
        
        .status-badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }
        
        .btn { padding: 0.6rem 1.2rem; border: none; border-radius: 10px; cursor: pointer; font-size: 0.9rem; margin-right: 0.5rem; }
        .btn-primary { background: #0072ff; color: white; }
        .btn-success { background: #22c55e; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-secondary { background: #64748b; color: white; }
        
        .file-link { display: inline-block; background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; color: #0072ff; margin: 0.3rem; }
        .file-link:hover { background: #e2e8f0; }
        
        .action-buttons { margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; }
        
        .back-link { display: inline-block; margin-top: 1rem; color: #0072ff; text-decoration: none; }
        
        @media (max-width: 768px) { .info-row { flex-direction: column; gap: 0.3rem; } .info-label { width: 100%; } }
    </style>
</head>
<body>

<div class="container">
    <!-- Application Details Card -->
    <div class="card">
        <h2><i class="fas fa-file-alt"></i> Application Details</h2>
        
        <div class="info-row">
            <div class="info-label">Application ID:</div>
            <div class="info-value">#<?php echo $application['id']; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-<?php echo $application['status']; ?>">
                    <?php echo ucfirst($application['status']); ?>
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Submitted On:</div>
            <div class="info-value"><?php echo date('F d, Y h:i A', strtotime($application['created_at'] ?? 'now')); ?></div>
        </div>
    </div>
    
    <!-- User Details Card -->
    <div class="card">
        <h2><i class="fas fa-user"></i> Applicant Details</h2>
        
        <div class="info-row">
            <div class="info-label">User ID:</div>
            <div class="info-value"><?php echo $user['id']; ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value"><?php echo htmlspecialchars($user['name']); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Specialty:</div>
            <div class="info-value"><?php echo htmlspecialchars($user['specialty'] ?? 'N/A'); ?></div>
        </div>
    </div>
    
    <!-- Documents Card -->
    <div class="card">
        <h2><i class="fas fa-paperclip"></i> Uploaded Documents</h2>
        
        <?php if($docs_result && $docs_result->num_rows > 0): ?>
            <?php while($doc = $docs_result->fetch_assoc()): ?>
                <a href="../uploads/<?php echo $doc['file_name']; ?>" class="file-link" target="_blank">
                    <i class="fas fa-file-pdf"></i> <?php echo basename($doc['file_name']); ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #64748b;">No documents uploaded.</p>
        <?php endif; ?>
    </div>
    
    <!-- Action Buttons Card -->
    <div class="card">
        <h2><i class="fas fa-tasks"></i> Actions</h2>
        
        <div class="action-buttons">
            <form method="POST" action="update_status.php" style="display: inline;">
                <input type="hidden" name="app_id" value="<?php echo $application['id']; ?>">
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this application?')">
                    <i class="fas fa-check-circle"></i> Approve
                </button>
            </form>
            
            <form method="POST" action="update_status.php" style="display: inline;">
                <input type="hidden" name="app_id" value="<?php echo $application['id']; ?>">
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this application?')">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
            </form>
            
            <a href="applications.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Applications
            </a>
        </div>
    </div>
    
    <a href="applications.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Applications List
    </a>
</div>

</body>
</html>