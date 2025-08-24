# AI Blog Platform

A full-featured blogging system built with **PHP** and **MySQL**, featuring user authentication, admin dashboard, and AI-powered blog assistance.

## 🚀 Features
- User authentication (Sign up, Login, Logout)  
- Admin panel to manage blogs and users  
- Create, edit, delete, and view blog posts  
- Search and filter blogs by category  
- Responsive design using Tailwind CSS  
- AI-assisted blog writing (via `ai_helper.php` and `ai_suggest.php`)  
- Role-based access (Admin & User)  
- Secure queries with prepared statements  

## 🛠 Tech Stack
- **Backend:** PHP, MySQL  
- **Frontend:** HTML, CSS, Tailwind CSS  
- **Extras:** Docker support for containerized setup  

## 📂 Project Structure
├── admin/ # Admin panel (manage blogs, users)
│ ├── blogs.php
│ └── users.php
│
├── assets/ # Static assets (CSS, JS, Images)
│
├── auth/ # Authentication system
│ ├── login.php
│ ├── logout.php
│ └── signup.php
│
├── blog/ # Blog-related pages
│ ├── create.php
│ ├── delete.php
│ ├── edit.php
│ └── view.php
│
├── config/ # Configurations
│ └── db.php
│
├── includes/ # Common includes
│ ├── admin_check.php
│ ├── auth_check.php
│ ├── footer.php
│ ├── functions.php
│ ├── header.php
│ └── navbar.php
│
├── public/ # User dashboard & public views
│ └── dashboard.php
│
├── vendor/ # AI helpers & third-party libraries
│ ├── ai_helper.php
│ └── ai_suggest.php
│
├── Dockerfile # Docker setup for containerized deployment
├── index.php # Homepage
└── README.md # Documentation

## ⚡ Installation
1. Clone or download this repository.  
2. Set up a local server (XAMPP, WAMP, or LAMP).  
3. Create a MySQL database (e.g., `ai_blog`).  
4. Import the SQL schema into the database.  
5. Update database credentials in `config/db.php`.  
6. Start Apache & MySQL, then visit `http://localhost/ai_blog/`.  

## 🔮 Future Improvements
- Rich text editor with image upload  
- Comments system for blogs  
- Advanced AI blog suggestions  
- Deployment on AWS or other cloud hosting  m
