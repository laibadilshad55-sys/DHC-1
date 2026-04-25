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
$success_msg = '';
$error_msg = '';

// Create uploads folder if not exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

if(isset($_POST['submit'])){
    $uid = $_SESSION['user_id'];
    
    // Debug
    echo "<!-- User ID: $uid -->";
    
    // Insert into applications table
    $insert_app = $conn->query("INSERT INTO applications (user_id, status) VALUES ('$uid', 'pending')");
    
    if($insert_app){
        $app_id = $conn->insert_id;
        
        // ========== ATS FEATURE 1: Generate Unique Application Number ==========
        $app_number = 'QAPP-' . date('Y') . '-' . str_pad($app_id, 5, '0', STR_PAD_LEFT);
        $conn->query("UPDATE applications SET app_number = '$app_number' WHERE id = '$app_id'");
        
        // ========== ATS FEATURE 2: Add to Timeline ==========
        $conn->query("INSERT INTO application_timeline (application_id, status, comment) 
                      VALUES ('$app_id', 'submitted', 'Application submitted by user')");
        
        // Handle file upload
        if(isset($_FILES['doc']) && $_FILES['doc']['error'] == 0){
            $file_name = time() . '_' . $_FILES['doc']['name'];
            $upload_path = "uploads/" . $file_name;
            
            if(move_uploaded_file($_FILES['doc']['tmp_name'], $upload_path)){
                $conn->query("INSERT INTO documents (application_id, file_name) VALUES ('$app_id', '$file_name')");
                
                // ========== ATS FEATURE 3: Document Upload Timeline ==========
                $conn->query("INSERT INTO application_timeline (application_id, status, comment) 
                              VALUES ('$app_id', 'document_uploaded', 'Document uploaded: $file_name')");
                
                $success_msg = "Application submitted successfully! Your Application ID is: " . $app_id . " | Tracking Number: " . $app_number;
            } else {
                $error_msg = "File upload failed!";
            }
        } else {
            $error_msg = "Please select a file to upload.";
        }
    } else {
        $error_msg = "Error submitting application: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Credentialing | Quad Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; }
        
        .premium-nav { background: rgba(11,31,58,0.95); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(255,255,255,0.12); }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 1rem 2.5rem; flex-wrap: wrap; }
        .logo-area { display: flex; align-items: center; gap: 12px; }
        .logo-icon { background: linear-gradient(135deg, #00c6ff, #0072ff); width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: white; }
        .logo-text { font-size: 1.7rem; font-weight: 800; background: linear-gradient(120deg, #FFFFFF, #B0E0FF); background-clip: text; -webkit-background-clip: text; color: transparent; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 500; }
        .nav-links a:hover { color: white; }
        .user-info { display: flex; align-items: center; gap: 1rem; color: white; background: rgba(255,255,255,0.1); padding: 0.4rem 1rem; border-radius: 40px; }
        .logout-btn { background: rgba(220,38,38,0.8); padding: 0.4rem 1rem; border-radius: 30px; text-decoration: none; color: white; }
        
        .main-content { max-width: 900px; margin: 2rem auto; padding: 0 2rem; }
        .form-card { background: white; border-radius: 2rem; overflow: hidden; box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1); }
        .form-header { background: linear-gradient(135deg, #0b1f3a, #1a3a4a); padding: 2rem; color: white; text-align: center; }
        .form-body { padding: 2.5rem; }
        .alert { padding: 1rem; border-radius: 16px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: #dcfce7; color: #166534; border-left: 4px solid #22c55e; }
        .alert-error { background: #fee2e2; color: #b91c1c; border-left: 4px solid #dc2626; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b; }
        .upload-area { border: 2px dashed #cbd5e1; border-radius: 1rem; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8fafc; }
        .upload-area:hover { border-color: #0072ff; background: #f0f9ff; }
        .file-input { display: none; }
        .btn-submit { width: 100%; background: linear-gradient(95deg, #00c6ff, #0072ff); color: white; border: none; padding: 1rem; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; }
        .btn-submit:hover { transform: translateY(-2px); }
        .footer-premium { background: #071626; padding: 2rem; text-align: center; color: #adc4dc; margin-top: 2rem; }
        @media (max-width: 768px) { .nav-container { flex-direction: column; } .form-body { padding: 1.5rem; } }
        
        /* ATS Features - Success Message Styling */
        .tracking-info {
            background: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 12px;
            padding: 0.8rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        .tracking-info i {
            color: #22c55e;
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="premium-nav">
    <div class="nav-container">
        <div class="logo-area"><div class="logo-icon">Q+</div><div class="logo-text">Quad Solutions</div></div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="apply.php">Apply</a>
            <a href="status.php">Status</a>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="form-card">
        <div class="form-header">
            <h1><i class="fas fa-file-medical"></i> Apply for Credentialing</h1>
            <p>Submit your application for medical credential verification</p>
        </div>
        
        <div class="form-body">
            <?php if($success_msg): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <?php echo $success_msg; ?>
                    <div class="tracking-info">
                        <i class="fas fa-qrcode"></i> 
                        <strong>ATS Tracking:</strong> You can track your application status anytime
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($error_msg): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div><?php echo $error_msg; ?></div>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-file-pdf"></i> Upload Document <span style="color:red;">*</span></label>
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #94a3b8;"></i></div>
                        <div class="upload-text">Click or drag & drop to upload</div>
                        <div class="upload-hint" style="font-size:0.75rem; color:#94a3b8;">Supported files: PDF, JPG, PNG (Max 5MB)</div>
                        <input type="file" name="doc" id="fileInput" class="file-input" required>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Application</button>
            </form>
        </div>
    </div>
</div>

<footer class="footer-premium">
    <div class="copyright">© 2026 Quad Solutions | Medical Credentialing System</div>
</footer>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    uploadArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
        if(e.target.files.length > 0) {
            uploadArea.style.borderColor = '#22c55e';
            uploadArea.style.background = '#f0fdf4';
        }
    });
</script>

</body>
</html>