📖 TALES — CHILDREN'S STORYBOOK PLATFORM & CMS

Final Project
Institution: Hidaya Institute of Science and Technology (HIST)
Developer: Aqsa Riyasat
🌐 Live Demo: [Tales | The Storybook Garden](https://blogapp.byethost18.com/)

Tales is a custom full-stack web application and Content Management System (CMS) engineered for published children's storybooks and educational tales. Built from the ground up using Core PHP, MySQL, JavaScript, HTML5, and CSS3, the platform focuses on robust server-side architecture, relational database design, session security, and dynamic content management.

==================================================
BACKEND ARCHITECTURE & CORE PHP FUNCTIONALITIES
==================================================

1. Authentication & Session State Management:
• Built custom PHP registration and login request handlers with server-side field validation.
• Implemented PHP Session API for state persistence and access token checks.
• Configured password reset workflow generating time-sensitive reset authorization tokens.

2. Role-Based Access Control (RBAC):
• Enforced administrative privilege guards (admin_auth.php) protecting sensitive CMS routes.
• Maintained user session guards (user_auth.php) restricting unauthorized database operations.

3. Relational MySQL Database Architecture:
• Designed normalized relational schema consisting of user, role, blog, post, category, comment, user_feedback, and following_blog tables.
• Executed dynamic SQL queries utilizing JOINs, WHERE clauses, and conditional filtering.

4. Server-Side Dual-Image Media Pipeline:
• Developed custom file processing scripts (create_post_action.php & update_post_action.php) for dual asset uploads (card_image & landscape_image).
• Enforced server-side file security validation including MIME type verification, extension checks, and file size limits.

5. Interactive Blog Following & Moderation Engine:
• Constructed relational follow/unfollow engine managing creator relationships via following_blog database mapping.
• Created dynamic comment submission handler with real-time database insertion and post link binding.
• Built user feedback processing script storing direct customer messages in user_feedback table.

6. Administrator CMS & Content Lifecycle Management:
• Full CRUD Engine: Executed Create, Read, Update, and Delete operations for posts, categories, and user profiles.
• Account Moderation: Implemented admin status toggles to Approve/Reject pending registrations and Activate/Deactivate accounts.
• Story Status Management: Built publishing status workflows controlling story visibility across the platform.

7. Asynchronous AJAX & Dynamic Header Integration:
• Implemented AJAX fetch API calls (components.js) communicating with includes/navigation_state.php to asynchronously verify session authentication and update site header actions dynamically with JSON responses without full page reloads.

==================================================
TECHNOLOGY STACK
==================================================

Backend Core: PHP (Procedural & Structured), Session API
Database: MySQL / MariaDB (Relational SQL Schema)
Asynchronous API: AJAX (Fetch API, JSON responses)
Frontend Core: HTML5, JavaScript (ES6+), Vanilla CSS3
UI Frameworks: Bootstrap 5.3
Animations: AOS (Animate On Scroll), CSS Keyframes
Mailer & Document Tools: PHPMailer Library, FPDF Library

==================================================
PROJECT DIRECTORY STRUCTURE
==================================================

Online_Blogging_Application/
├── actions/
│   ├── add_comment_action.php          Comment Submission Handler
│   ├── admin_add_user_action.php       Admin Add User Handler
│   ├── blog_action.php                 Blog Creation & Update Handler
│   ├── category_action.php             Category Add & Update Handler
│   ├── change_password_action.php      Password Update Handler
│   ├── comment_action.php              Comment Edit Handler
│   ├── comment_status_action.php       Comment Approve & Reject Handler
│   ├── create_blog_action.php          New Blog Registration Handler
│   ├── create_post_action.php          Story Creation & Dual Image Upload
│   ├── feedback_action.php             Contact Feedback Handler
│   ├── follow_action.php               Blog Follow & Unfollow Handler
│   ├── forgot_password_action.php      Password Reset Token Handler
│   ├── login_action.php                User Authentication Handler
│   ├── post_status_action.php          Story Publish & Inactive Toggle
│   ├── register_action.php             User Registration Handler
│   ├── settings_action.php             System Settings Handler
│   ├── update_comment_action.php       Comment Modification Handler
│   ├── update_post_action.php          Story Update & Image Replacement
│   ├── update_profile_action.php       User Profile Info Handler
│   ├── update_user_action.php          Admin User Management Handler
│   └── user_status_action.php          User Approval & Activation Handler
├── admin/
│   ├── blog_form.php                   Blog Creation & Edit Form View
│   ├── blogs.php                       Admin Blogs Catalog Table
│   ├── categories.php                  Category Management View
│   ├── comments.php                    Comment Moderation View
│   ├── dashboard.php                   Admin Analytics Overview
│   ├── edit_post.php                   Edit Story View
│   ├── feedback.php                    Customer Feedback Review View
│   ├── post_form.php                   Add Story Form with Dual Uploads
│   ├── posts.php                       Story Catalog Management
│   ├── settings.php                    Admin Settings Dashboard
│   └── users.php                       User Accounts Table View
├── assets/
│   ├── css/
│   │   ├── blog_theme.php              Dynamic Blog Theming Engine
│   │   └── styles.css                  Unified Design System & Stylesheet
│   ├── js/
│   │   ├── admin-components.js         Admin Panel UI Controllers
│   │   ├── app.js                      Public Animations & Interactive JS
│   │   └── components.js               Dynamic Header State Engine
│   └── images/
│       ├── about/                      About Page Showcase Illustrations
│       ├── auth/                       Login & Register Graphic Assets
│       ├── avatars/                    User Avatars & Admin Profile Pictures
│       ├── hero/                       Storybook Garden Hero Illustrations
│       └── posts/                      Story Card & Landscape Cover Images
├── config/
│   ├── database.php                    MySQL Database Connection Config
│   └── email.php                       SMTP Mail Configuration
├── database/
│   └── 24870_aqsa_online_blogging_application.sql
├── includes/
│   ├── admin_auth.php                  Admin Route Protection Guard
│   ├── navigation_state.php            Dynamic Header State API Endpoint
│   ├── popup_message.php               System Notification Modal Component
│   ├── send_email.php                  Email Dispatch Helper Function
│   ├── session.php                     Session Initialization & State Guard
│   └── user_auth.php                   User Route Protection Guard
├── about.php                           About Page View
├── blogs.php                           Blog Directory & Reading Hub
├── categories.php                      Category Filtered Story Catalog
├── contact.php                         Contact & Feedback Form Page
├── forgot_password.php                 Password Recovery Page
├── index.php                           Home Page & Storybook Showcase
├── login.php                           User & Admin Sign In Page
├── logout.php                          Session Termination Handler
├── post.php                            Single Story Article & Comments
├── profile.php                         User Profile & Dashboard View
├── register.php                        User Registration Page
├── .gitignore                          Standard Version Control Exclusions
└── README.md                           Project Documentation

==================================================
INSTALLATION & LOCAL SETUP
==================================================

1. Move project repository into XAMPP htdocs directory:
   C:\xampp\htdocs\Online_Blogging_Application

2. Start Apache and MySQL services in XAMPP Control Panel.

3. Import Database:
   Open phpMyAdmin at http://localhost/phpmyadmin/
   Create database named: 24870_aqsa_online_blogging_application
   Import SQL dump file located at: database/24870_aqsa_online_blogging_application.sql

4. Access Application:
   • 🌐 Live Hosted Version: [Tales | The Storybook Garden](https://blogapp.byethost18.com/)
   • Localhost Version: http://localhost/Online_Blogging_Application/

==================================================
DEMO CREDENTIALS
==================================================

Administrator: admin@tales.example / Password: admin123
Regular User: sara@gmail.com / Password: sara12345
Regular User: ali@gmail.com / Password: ali12345

==================================================
ACKNOWLEDGMENTS
==================================================

Developed as the Final Project for Hidaya Institute of Science and Technology (HIST). Special thanks to the instructors and mentors for their guidance.
