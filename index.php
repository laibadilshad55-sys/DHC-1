<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Quad Solutions | Premium Medical Credentialing</title>
    <!-- Google Fonts + Icons + AOS + Swiper -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1A2C3E;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        /* Premium Glassmorphism Navbar */
        .premium-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(11, 31, 58, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2.5rem;
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
            letter-spacing: -0.5px;
            background: linear-gradient(120deg, #FFFFFF, #B0E0FF);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: 0.2s;
            letter-spacing: -0.2px;
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
            border-radius: 2px;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-premium-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.4);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            transition: 0.2s;
        }

        .btn-premium-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #00c6ff;
        }

        /* Hero Premium Section */
        .hero-premium {
            position: relative;
            background: radial-gradient(circle at 10% 30%, #0A1A2F, #05121E);
            overflow: hidden;
        }

        .hero-bg-glow {
            position: absolute;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(0,198,255,0.15) 0%, rgba(0,0,0,0) 70%);
            top: -10%;
            right: -20%;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 5rem 2rem 7rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .hero-left {
            flex: 1;
            min-width: 280px;
        }

        .hero-left .badge {
            background: rgba(0,198,255,0.2);
            backdrop-filter: blur(5px);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #00e0ff;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,198,255,0.3);
        }

        .hero-left h1 {
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.2;
            background: linear-gradient(to right, #ffffff, #B3E4FF);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
        }

        .hero-left .gradient-text {
            color: #00c6ff;
            background: none;
            -webkit-background-clip: unset;
            background-clip: unset;
        }

        .hero-left p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.5;
            max-width: 550px;
            margin: 1.5rem 0;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary-glow {
            background: linear-gradient(95deg, #00c6ff, #0072ff);
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 44px;
            font-weight: 700;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(0,114,255,0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0,114,255,0.4);
        }

        .btn-outline-premium {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.5);
            padding: 0.9rem 2rem;
            border-radius: 44px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-premium:hover {
            background: rgba(255,255,255,0.1);
            border-color: #00c6ff;
        }

        .hero-right {
            flex: 0.8;
            min-width: 280px;
            display: flex;
            justify-content: center;
        }

        .floating-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 25px 40px rgba(0,0,0,0.2);
            text-align: center;
            width: 280px;
        }

        .floating-card i {
            font-size: 3rem;
            color: #00c6ff;
            margin-bottom: 1rem;
        }

        /* Sections premium */
        .section-premium {
            padding: 5rem 2rem;
        }

        .container-premium {
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #1A2C3E, #2C5364);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .section-sub {
            text-align: center;
            color: #5A6E7A;
            max-width: 650px;
            margin: 0 auto 3rem auto;
            font-size: 1.1rem;
        }

        /* Cards modern */
        .card-grid {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 2rem;
            padding: 2rem 1.8rem;
            width: 300px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            text-align: center;
        }

        .premium-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 40px -12px rgba(0,114,255,0.15);
            border-color: rgba(0,198,255,0.2);
        }

        .card-icon {
            background: linear-gradient(145deg, #EFF7FF, #E0F0FF);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 30px;
            margin: 0 auto 1.5rem auto;
            font-size: 2rem;
            color: #0072ff;
        }

        .premium-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .premium-card p {
            color: #4b5e6c;
            line-height: 1.5;
        }

        /* Stats Banner */
        .stats-banner {
            background: linear-gradient(110deg, #0A1C2F, #08212E);
            margin: 1rem 0;
            border-radius: 3rem;
            padding: 3rem 2rem;
        }

        .stats-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 2rem;
            text-align: center;
        }

        .stat-item h2 {
            font-size: 2.7rem;
            font-weight: 800;
            color: #00c6ff;
        }

        .stat-item p {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

        /* Testimonials slider */
        .testimonial-slider {
            background: #F9FDFF;
            border-radius: 2rem;
            padding: 2rem;
        }

        .swiper-slide {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            border: 1px solid #eef2f6;
        }

        .testimonial-text {
            font-style: italic;
            font-size: 1rem;
            color: #2c3e4e;
            line-height: 1.5;
        }

        .testimonial-author {
            margin-top: 1.2rem;
            font-weight: 700;
            color: #0072ff;
        }

        /* CTA premium */
        .cta-premium {
            background: linear-gradient(135deg, #0b1f3a, #123a4b);
            border-radius: 2rem;
            text-align: center;
            padding: 4rem 2rem;
            margin-top: 2rem;
        }

        .cta-premium h2 {
            font-size: 2.2rem;
            color: white;
        }

        /* Footer premium */
        .footer-premium {
            background: #071626;
            padding: 3rem 2rem 1.5rem;
            color: #adc4dc;
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(120deg, #fff, #00c6ff);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .footer-links a {
            color: #adc4dc;
            text-decoration: none;
            margin-left: 1.5rem;
            transition: 0.2s;
        }

        .footer-links a:hover {
            color: #00c6ff;
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem;
            font-size: 0.85rem;
        }

        @media (max-width: 900px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            .hero-left h1 {
                font-size: 2.6rem;
            }
            .hero-container {
                flex-direction: column;
                text-align: center;
                gap: 2rem;
            }
            .btn-group {
                justify-content: center;
            }
            .hero-left p {
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>
<body>

<!-- PREMIUM NAVBAR (Sticky glassmorphism) -->
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
            <a href="admin/login.php" class="btn-premium-outline">Admin Panel</a>
        </div>
    </div>
</div>

<!-- HERO PREMIUM SECTION -->
<section class="hero-premium">
    <div class="hero-bg-glow"></div>
    <div class="hero-container">
        <div class="hero-left" data-aos="fade-right" data-aos-duration="800">
            <div class="badge"><i class="fas fa-shield-alt"></i> HIPAA Compliant · Enterprise Grade</div>
            <h1>Medical Credentialing <span class="gradient-text">Reimagined</span></h1>
            <p>Quad Solutions delivers a powerful, secure ecosystem for healthcare credentialing — manage licenses, certifications, and provider data with unmatched efficiency and intelligence.</p>
            <div class="btn-group">
                <a href="login.php" class="btn-primary-glow"><i class="fas fa-arrow-right-to-bracket"></i> Access System</a>
                <a href="registration.php" class="btn-outline-premium"><i class="fas fa-user-plus"></i> Register Provider</a>
            </div>
        </div>
        <div class="hero-right" data-aos="fade-left" data-aos-duration="800">
            <div class="floating-card">
                <i class="fas fa-notes-medical"></i>
                <h3 style="color:white;">Credentialing Suite</h3>
                <p style="font-size:0.85rem; color:#ccc;">Real-time verification · Automated renewals</p>
                <div style="margin-top:1rem; color:#00c6ff;"><i class="fas fa-check-circle"></i> 99.9% uptime</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION PREMIUM -->
<div class="section-premium">
    <div class="container-premium">
        <h2 class="section-title" data-aos="fade-up">System Capabilities</h2>
        <p class="section-sub" data-aos="fade-up" data-aos-delay="100">Engineered for modern healthcare organizations, ensuring compliance and speed.</p>
        <div class="card-grid">
            <div class="premium-card" data-aos="zoom-in" data-aos-delay="150">
                <div class="card-icon"><i class="fas fa-lock"></i></div>
                <h3>Zero-Trust Security</h3>
                <p>Role-based access control, multi-layer encryption, and audit logs for complete data governance.</p>
            </div>
            <div class="premium-card" data-aos="zoom-in" data-aos-delay="250">
                <div class="card-icon"><i class="fas fa-file-alt"></i></div>
                <h3>Credential Tracking</h3>
                <p>Centralized repository for licenses, NPI, DEA, board certs with expiry alerts and document management.</p>
            </div>
            <div class="premium-card" data-aos="zoom-in" data-aos-delay="350">
                <div class="card-icon"><i class="fas fa-user-shield"></i></div>
                <h3>Admin Intelligence</h3>
                <p>Granular dashboards, verification workflows, and bulk provider updates to reduce manual friction.</p>
            </div>
        </div>
    </div>
</div>

<!-- STATS SECTION (Social Proof Premium) -->
<div class="container-premium" style="padding: 0 2rem;">
    <div class="stats-banner" data-aos="flip-up">
        <div class="stats-grid">
            <div class="stat-item"><h2>450+</h2><p>Healthcare Organizations</p></div>
            <div class="stat-item"><h2>12K+</h2><p>Verified Providers</p></div>
            <div class="stat-item"><h2>99.8%</h2><p>Compliance Accuracy</p></div>
            <div class="stat-item"><h2>24/7</h2><p>Secure Access</p></div>
        </div>
    </div>
</div>

<!-- ABOUT + PREMIUM FEATURE SHOWCASE -->
<div class="section-premium" style="background: #F8FAFE;">
    <div class="container-premium">
        <div style="display: flex; flex-wrap: wrap; gap: 3rem; align-items: center;">
            <div style="flex:1" data-aos="fade-right">
                <span style="font-weight: 600; color:#0072ff; letter-spacing: 1px;">QUAD INTELLIGENCE</span>
                <h2 style="font-size: 2rem; margin: 1rem 0;">Streamlined credentialing <br> for future-ready healthcare</h2>
                <p style="color:#3a5468; line-height: 1.5;">Quad Solutions eliminates redundant paperwork and offers a centralized hub where medical staff, credentialing coordinators, and admins collaborate seamlessly. With automated primary source verification tracking, you maintain real-time readiness for accreditation surveys.</p>
                <div style="margin-top: 1.5rem;">
                    <i class="fas fa-check-circle" style="color:#28a745;"></i> <span style="margin-left: 0.5rem;">Real-time renewal alerts</span><br>
                    <i class="fas fa-check-circle" style="color:#28a745; margin-top: 0.7rem;"></i> <span style="margin-left: 0.5rem;">Seamless document uploads & OCR</span><br>
                    <i class="fas fa-check-circle" style="color:#28a745; margin-top: 0.7rem;"></i> <span style="margin-left: 0.5rem;">Audit-ready reporting</span>
                </div>
            </div>
            <div style="flex:1; background: white; border-radius: 2rem; padding: 2rem; box-shadow: 0 15px 30px rgba(0,0,0,0.05);" data-aos="fade-left">
                <i class="fas fa-chart-line" style="font-size: 2rem; color:#0072ff;"></i>
                <h3 style="margin: 1rem 0;">Why Quad Solutions?</h3>
                <p>Built by healthcare IT experts, our platform reduces credentialing time by 40% and helps you maintain continuous compliance with NCQA, TJC, and CMS standards.</p>
                <hr style="margin: 1rem 0; background: #e0e9f0; border: none; height: 1px;">
                <div><i class="fas fa-database"></i> Centralized provider master index</div>
                <div class="mt-2"><i class="fas fa-sync-alt"></i> Automated license verification</div>
            </div>
        </div>
    </div>
</div>

<!-- TESTIMONIAL SLIDER (Premium feel) -->
<div class="container-premium" style="margin: 2rem auto;">
    <h2 class="section-title" data-aos="fade-up">Trusted by Healthcare Leaders</h2>
    <div class="testimonial-slider" data-aos="fade-up">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-text">“Quad Solutions transformed our credentialing workflow. The intuitive dashboard and automated renewals saved our team over 20 hours per week.”</div>
                    <div class="testimonial-author">— Dr. Emily R., CMO at HealthFirst Medical</div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-text">“Security and compliance were our top concerns. Quad delivered beyond expectations — plus the support team is outstanding.”</div>
                    <div class="testimonial-author">— Michael T., Director of Operations</div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-text">“The admin panel gives us complete control over provider data. We reduced manual verification errors drastically.”</div>
                    <div class="testimonial-author">— Sarah K., Credentialing Manager</div>
                </div>
            </div>
            <div class="swiper-pagination" style="margin-top: 20px; position: relative;"></div>
        </div>
    </div>
</div>

<!-- CTA SECTION Premium -->
<div class="container-premium" style="margin-bottom: 3rem;">
    <div class="cta-premium" data-aos="zoom-in-up">
        <h2>Ready to modernize credentialing?</h2>
        <p style="color: #C9E9FF; margin: 1rem auto; max-width: 600px;">Join the network of premier healthcare organizations using Quad Solutions.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="registration.php" class="btn-primary-glow" style="background: white; color:#0b1f3a; box-shadow: none;">Get Started →</a>
            <a href="login.php" class="btn-outline-premium" style="border-color: white; color: white;">Access Portal</a>
        </div>
    </div>
</div>

<!-- PREMIUM FOOTER -->
<footer class="footer-premium">
    <div class="footer-content">
        <div>
            <div class="footer-logo">Quad Solutions</div>
            <p style="margin-top: 0.8rem; max-width: 260px;">Medical Credentialing Management System · Next-gen provider data intelligence.</p>
            <div style="margin-top: 1rem; font-size: 1.2rem;">
                <i class="fab fa-linkedin" style="margin-right: 1rem;"></i>
                <i class="fab fa-twitter" style="margin-right: 1rem;"></i>
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="registration.php">Register</a>
            <a href="admin/login.php">Admin</a>
        </div>
    </div>
    <div class="copyright">
        © 2026 Quad Solutions | Medical Credentialing System | HIPAA Compliant Framework
    </div>
</footer>

<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 80
    });

    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 2.5,
            }
        }
    });
</script>
</body>
</html>