# Car Wash Management System

A web-based management system for running and tracking car-wash operations, built with PHP, MySQL and a simple HTML/CSS/JS front-end.

---

## ✨ Features
- Admin dashboard: manage wash-plans, services, bookings, customers.  
- Customer interface (if included): view available plans, make bookings.  
- MySQL database backend to store service plans, customers, transactions.  
- Uses PHP for server-side logic and MySQL for data storage.  
- Front-end built with HTML, CSS (Bootstrap or custom) and Javascript for interactivity.  
- Simple installation: import SQL file, configure DB settings, run via XAMPP/WAMP.

---

## 🧰 Tech Stack
- **Server-side:** PHP  
- **Database:** MySQL  
- **Front-end:** HTML, CSS, JavaScript  
- **Local development:** XAMPP / WAMP / LAMP  
- One SQL file included for database initialization.

---

## 🛠️ Setup & Installation
1. Clone or download this repository:  
   ```bash
   git clone https://github.com/Akshayy-y/Car-Wash-Management-System.git
   Place the project folder inside your server web-root (e.g., C:\xampp\htdocs\Car-Wash-Management-System for XAMPP on Windows).

Start the web server & MySQL server (via XAMPP/WAMP).

In PhpMyAdmin, create a new database (for example cwmsdb).

Import the included SQL file (found in SQL File/cwmsdb.sql).

Open the project in your browser: http://localhost/Car-Wash-Management-System/.

Navigate to the admin panel and log in with the default credentials (if any) — then update them for security.

🧍 User Roles

Administrator: full access — manage plans, bookings, customers, view reports.

Customer/User (if applicable): view available services/plans, make bookings.


✅ How to Use

Admin logs in → adds/edits wash plans (e.g., basic wash, premium wash).

Admin sees list of bookings and can update status (pending → in-progress → completed).

Customer (if implemented) selects a plan, books a slot, and pays (if payment feature exists).

Admin reviews reports, exports data (optional).


📂 Folder Structure

cwms/
  admin/         ← Admin-panel PHP files, CSS, JS  
  
  SQL File/      ← Database schema & seed file (cwmsdb.sql)  
  
  includes/      ← Common includes (DB connection, config)
  
  lib/           ← External libraries (e.g., jQuery plugins)  
  
  index.php      ← Landing page  
  
  washing-plans.php ← List of available service plans 
  
  …              ← Other files  


🚨 Important Notes

After installation, change default credentials immediately and secure your database connection.

On Windows, you might see warnings in Git like LF will be replaced by CRLF — these are benign and relate to line-ending conversions.

Before deploying to production, ensure error reporting is turned off and the database user has minimal permissions.
📌 Contributions

Contributions are welcome! If you find bugs or want to add features (e.g., payment gateway integration, slot-based scheduling, mobile responsive enhancements) — feel free to open an issue or submit a pull request.

👤 Author

Created by Akshayy-y — GitHub: @Akshayy-y
