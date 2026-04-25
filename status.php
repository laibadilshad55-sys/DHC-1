<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// Debug - Check if user exists in session
$debug_msg = '';
if(!$uid){
    $debug_msg = "Session user_id not set!";
}

// Query applications for this user
$res = $conn->query("SELECT * FROM applications WHERE user_id='$uid' ORDER BY id DESC");
$total_applications = $res->num_rows;

// Get latest application for display
$app = $res->fetch_assoc();

// Get timeline for this application
$timeline = [];
if($app){
    $timeline_res = $conn->query("SELECT * FROM application_timeline WHERE application_id='{$app['id']}' ORDER BY created_at ASC");
    while($row = $timeline_res->fetch_assoc()){
        $timeline[] = $row;
    }
}

// Also get count of total applications for badge
$all_apps = $conn->query("SELECT COUNT(*) as total FROM applications WHERE user_id='$uid'");
$app_count = $all_apps->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; overflow-x: hidden; }
        .premium-nav { background: rgba(11, 31, 58, 0.95); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(255,255,255,0.12); position: sticky; top: 0; z-index: 1000; }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 1rem 2.5rem; flex-wrap: wrap; }
        .logo-area { display: flex; align-items: center; gap: 12px; }
        .logo-icon { background: linear-gradient(135deg, #00c6ff, #0072ff); width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: white; }
        .logo-text { font-size: 1.7rem; font-weight: 800; background: linear-gradient(120deg, #FFFFFF, #B0E0FF); background-clip: text; -webkit-background-clip: text; color: transparent; }
        .nav-links { display: flex; gap: 2rem; align-items: center; flex-wrap: wrap; }
        .nav-links a { color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 500; transition: 0.2s; position: relative; }
        .nav-links a:hover { color: white; }
        .nav-links a::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 0%; height: 2px; background: linear-gradient(90deg, #00c6ff, #0072ff); transition: 0.3s; }
        .nav-links a:hover::after { width: 100%; }
        .user-info { display: flex; align-items: center; gap: 1rem; color: white; background: rgba(255,255,255,0.1); padding: 0.4rem 1rem; border-radius: 40px; }
        .logout-btn { background: rgba(220, 38, 38, 0.8); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.85rem; text-decoration: none; color: white; }
        .logout-btn:hover { background: #dc2626; }
        .main-content { max-width: 1000px; margin: 2rem auto; padding: 0 2rem; }
        .status-card { background: white; border-radius: 2rem; overflow: hidden; box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1); }
        .status-header { background: linear-gradient(135deg, #0b1f3a, #1a3a4a); padding: 2rem; color: white; text-align: center; }
        .status-header h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .status-body { padding: 2.5rem; text-align: center; }
        .application-count { background: #e0f2fe; padding: 0.5rem 1rem; border-radius: 50px; display: inline-block; margin-bottom: 1rem; font-size: 0.85rem; }
        .status-badge { display: inline-block; padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 700; font-size: 1rem; margin-bottom: 1.5rem; }
        .status-pending { background: #fef3c7; color: #d97706; border-left: 4px solid #f59e0b; }
        .status-approved { background: #dcfce7; color: #166534; border-left: 4px solid #22c55e; }
        .status-rejected { background: #fee2e2; color: #b91c1c; border-left: 4px solid #dc2626; }
        .status-icon { width: 100px; height: 100px; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 3rem; }
        .icon-pending { background: #fef3c7; color: #d97706; }
        .details-section { background: #f8fafc; border-radius: 1rem; padding: 1.5rem; margin-top: 1.5rem; text-align: left; }
        .details-title { font-weight: 700; margin-bottom: 1rem; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }
        .detail-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e2e8f0; }
        .detail-label { font-weight: 600; color: #475569; }
        .detail-value { color: #1e293b; }
        
        /* ATS Timeline Styles */
        .timeline-section { background: #f8fafc; border-radius: 1rem; padding: 1.5rem; margin-top: 1.5rem; text-align: left; }
        .timeline-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #e2e8f0; position: relative; }
        .timeline-item:last-child { border-bottom: none; }
        .timeline-icon { width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .timeline-icon.submitted { background: #e0f2fe; color: #0369a1; }
        .timeline-icon.review { background: #fef3c7; color: #d97706; }
        .timeline-icon.approved { background: #dcfce7; color: #166534; }
        .timeline-icon.rejected { background: #fee2e2; color: #b91c1c; }
        .timeline-content { flex: 1; }
        .timeline-status { font-weight: 700; margin-bottom: 0.25rem; }
        .timeline-comment { color: #64748b; font-size: 0.85rem; }
        .timeline-date { color: #94a3b8; font-size: 0.7rem; margin-top: 0.25rem; }
        
        /* Tracking Number Badge */
        .tracking-badge { background: linear-gradient(135deg, #0b1f3a, #1a3a4a); color: white; padding: 0.5rem 1rem; border-radius: 50px; display: inline-block; margin-bottom: 1rem; font-family: monospace; font-size: 1rem; }
        .tracking-badge i { margin-right: 8px; color: #00c6ff; }
        
        .btn-primary { background: linear-gradient(95deg, #00c6ff, #0072ff); color: white; padding: 0.8rem 1.8rem; border-radius: 40px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .btn-outline { background: transparent; border: 1.5px solid #00c6ff; color: #0072ff; padding: 0.8rem 1.8rem; border-radius: 40px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .action-buttons { display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap; }
        .no-application { text-align: center; padding: 2rem; }
        .no-application i { font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem; }
        .footer-premium { background: #071626; padding: 2rem; text-align: center; color: #adc4dc; margin-top: 2rem; }
        
        @media (max-width: 768px) { .nav-container { flex-direction: column; gap: 0.5rem; } .status-body { padding: 1.5rem; } .detail-row { flex-direction: column; gap: 0.3rem; } }
    </style>
</head>
<body>

<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area">
            <div class="logo-icon">Q+</div>
            <div class="logo-text">Quad Solutions</div>
        </div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="apply.php">Apply</a>
            <a href="status.php" style="color: white;">Status</a>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="status-card">
        <div class="status-header">
            <h1><i class="fas fa-clipboard-list"></i> Application Status</h1>
            <p>Track your credentialing application progress</p>
        </div>
        
        <div class="status-body">
            <?php if($debug_msg): ?>
            <div class="alert alert-error" style="background:#fee2e2; padding:1rem; border-radius:12px; margin-bottom:1rem;">
                <?php echo $debug_msg; ?>
            </div>
            <?php endif; ?>
            
            <?php if($app): ?>
            <!-- Show total applications count -->
            <div class="application-count">
                <i class="fas fa-files"></i> Total Applications: <?php echo $total_applications; ?>
            </div>
            
            <!-- ATS: Show Tracking Number -->
            <?php if(!empty($app['app_number'])): ?>
            <div class="tracking-badge">
                <i class="fas fa-qrcode"></i> Tracking Number: <?php echo $app['app_number']; ?>
            </div>
            <?php endif; ?>
            
            <?php 
                $status = strtolower(trim($app['status'] ?? 'pending'));
                $status_class = 'status-pending';
                $icon_class = 'icon-pending';
                $status_text = 'Pending';
                $icon = 'fa-hourglass-half';
                $message = '⏳ Your application is pending review.';
                $submessage = 'Our team will review your application shortly.';
                
                if($status == 'approved'){
                    $status_class = 'status-approved';
                    $icon_class = 'icon-approved';
                    $status_text = 'Approved';
                    $icon = 'fa-check-circle';
                    $message = '🎉 Congratulations! Your application has been approved!';
                    $submessage = 'You can now access all credentialing features.';
                } elseif($status == 'rejected'){
                    $status_class = 'status-rejected';
                    $icon_class = 'icon-rejected';
                    $status_text = 'Rejected';
                    $icon = 'fa-times-circle';
                    $message = '⚠️ Your application was not approved.';
                    $submessage = 'Please contact support or submit a new application.';
                }
            ?>
            
            <div class="status-icon <?php echo $icon_class; ?>">
                <i class="fas <?php echo $icon; ?>"></i>
            </div>
            
            <div class="status-badge <?php echo $status_class; ?>">
                <i class="fas <?php echo $icon; ?>"></i> <?php echo $status_text; ?>
            </div>
            
            <div class="status-message" style="font-size:1.2rem; font-weight:600; margin-bottom:0.5rem;">
                <?php echo $message; ?>
            </div>
            <div class="status-submessage" style="color:#64748b; margin-bottom:1.5rem;">
                <?php echo $submessage; ?>
            </div>
            
            <div class="details-section">
                <div class="details-title">
                    <i class="fas fa-info-circle"></i> Application Details
                </div>
                <div class="detail-row">
                    <span class="detail-label">Application ID:</span>
                    <span class="detail-value">#<?php echo $app['id']; ?></span>
                </div>
                <?php if(!empty($app['app_number'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Tracking Number:</span>
                    <span class="detail-value"><strong><?php echo $app['app_number']; ?></strong></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">Current Status:</span>
                    <span class="detail-value">
                        <span class="status-badge <?php echo $status_class; ?>" style="display: inline-block; padding: 0.2rem 0.8rem; font-size: 0.7rem;">
                            <?php echo $status_text; ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Submitted On:</span>
                    <span class="detail-value">
                        <?php 
                            if(isset($app['created_at'])){
                                echo date('F d, Y h:i A', strtotime($app['created_at']));
                            } else {
                                echo date('F d, Y');
                            }
                        ?>
                    </span>
                </div>
            </div>
            
            <!-- ATS: Application Timeline -->
            <?php if(!empty($timeline)): ?>
            <div class="timeline-section">
                <div class="details-title">
                    <i class="fas fa-history"></i> Application Timeline
                </div>
                <?php foreach($timeline as $event): ?>
                <div class="timeline-item">
                    <div class="timeline-icon <?php echo $event['status']; ?>">
                        <?php if($event['status'] == 'submitted'): ?>
                            <i class="fas fa-file-alt"></i>
                        <?php elseif($event['status'] == 'under_review'): ?>
                            <i class="fas fa-search"></i>
                        <?php elseif($event['status'] == 'approved'): ?>
                            <i class="fas fa-check-circle"></i>
                        <?php elseif($event['status'] == 'rejected'): ?>
                            <i class="fas fa-times-circle"></i>
                        <?php elseif($event['status'] == 'document_uploaded'): ?>
                            <i class="fas fa-upload"></i>
                        <?php else: ?>
                            <i class="fas fa-bell"></i>
                        <?php endif; ?>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-status">
                            <?php 
                                if($event['status'] == 'submitted') echo 'Application Submitted';
                                elseif($event['status'] == 'under_review') echo 'Under Review';
                                elseif($event['status'] == 'approved') echo 'Application Approved';
                                elseif($event['status'] == 'rejected') echo 'Application Rejected';
                                elseif($event['status'] == 'document_uploaded') echo 'Document Uploaded';
                                else echo ucfirst(str_replace('_', ' ', $event['status']));
                            ?>
                        </div>
                        <?php if(!empty($event['comment'])): ?>
                        <div class="timeline-comment"><?php echo htmlspecialchars($event['comment']); ?></div>
                        <?php endif; ?>
                        <div class="timeline-date">
                            <i class="fas fa-calendar-alt"></i> <?php echo date('F d, Y h:i A', strtotime($event['created_at'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="no-application">
                <i class="fas fa-folder-open"></i>
                <h3>No Applications Yet</h3>
                <p>Start your credentialing journey by submitting your first application.</p>
                <br>
                <a href="apply.php" class="btn-primary">
                    <i class="fas fa-pen-alt"></i> Apply Now
                </a>
            </div>
            <?php endif; ?>
            
            <div class="action-buttons">
                <a href="dashboard.php" class="btn-outline">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <?php if($app): ?>
                <a href="apply.php" class="btn-outline">
                    <i class="fas fa-plus-circle"></i> New Application
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer-premium">
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | Secure Status Portal
    </div>
</footer>

</body>
</html>