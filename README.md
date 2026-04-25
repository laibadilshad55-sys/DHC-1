# 🏥 Medical Credentialing System - Quad Solutions

A **full-stack hospital credentialing system** built with PHP and MySQL. It allows medical professionals to register, submit applications, and track their credentialing status in real-time. Includes an admin panel for managing users, verifying documents, and approving/rejecting applications.

---

## ✨ Features

### 👨‍⚕️ For Medical Professionals
- 📝 User registration & login
- 📄 Submit credentialing application
- 📎 Upload documents (licenses, certificates, degrees)
- 🔍 Track application status in real-time
- 👤 Profile management
- 🔔 Email notifications on status updates

### 👑 For Admin
- 📊 Dashboard with analytics
- 👥 Manage all registered users
- ✅ Approve/reject applications with comments
- 📋 Verify uploaded documents
- 🔒 Secure admin authentication
- 📈 Track application history

---

## 🛠️ Tech Stack

| Category | Technology |
|----------|------------|
| **Backend** | PHP (Core) |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Authentication** | Custom PHP sessions |
| **File Uploads** | PHP native |
| **Version Control** | Git & GitHub |

---

## 📁 Project Structure
edical_credentialing/
├── includes/ # Header, footer, config files
├── uploads/ # User uploaded documents
├── adminStatus.php # Admin status management
├── application.php # Credentialing application form
├── apply.php # Apply for credentialing
├── dashboard.php # User dashboard
├── forgot.php # Forgot password functionality
├── index.php # Homepage
├── login.php # User/Admin login
├── login_process.php # Login authentication
├── logout.php # Logout handler
├── profile.php # User profile management
├── registration.php # New user registration
├── register_process.php # Registration handler
├── reset_password.php # Password reset
├── status.php # Application status view
├── test.php # Testing file
├── medical_system.sql # Database structure
├── .env # Environment variables (ignored)
└── .env.example # Environment template

text

---

## 🚀 Installation Guide

### Prerequisites
- XAMPP / WAMP / LAMP
- PHP 7.4+
- MySQL 5.7+

### Step-by-Step Setup

1. **Clone the repository**
   
   git clone https://github.com/laibadilshad55-sys/DHC-1.git
Move to htdocs

mv DHC-1 C:/xampp/htdocs/
Create database

Open phpMyAdmin: http://localhost/phpmyadmin

Create database: medical_credentialing






Right-click uploads folder

Properties → Security → Allow full control

Run the application

Start Apache & MySQL in XAMPP

Visit: http://localhost/DHC-1


📊 Application Workflow
text
1. User Registers
   ↓
2. User Logs In
   ↓
3. Fills Application Form
   ↓
4. Uploads Documents
   ↓
5. Admin Reviews Application
   ↓
6. Admin Approves/Rejects
   ↓
7. User Receives Notification
   ↓
8. User Checks Status Dashboard
🔒 Security Features
✅ .env for sensitive data (ignored by Git)

✅ Password hashing (bcrypt/password_hash())

✅ SQL injection prevention (prepared statements/mysqli)

✅ XSS protection (htmlspecialchars)

✅ CSRF tokens on forms

✅ Session-based authentication

🚀 Future Enhancements
Two-factor authentication (2FA)

Email verification on registration

Document auto-verification using AI

Mobile responsive PWA version

PDF report generation

Multi-language support

Payment integration for application fees

👩‍💻 Author
Laiba Dilshad

GitHub: @laibadilshad55-sys

Project: Full-Stack Development Project

📄 License
This project is for educational purposes as part of academic coursework.

🙏 Acknowledgments
Quad Solutions for project requirements

PHP & MySQL community

Open-source libraries used

📞 Support
For issues or questions, please:

Check the existing issues on GitHub

Contact the project maintainer

Refer to the documentation

⭐ Show Your Support
If this project helped you, please give it a ⭐ on GitHub!

Built with ❤️ using PHP & MySQL





g

