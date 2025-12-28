# 🎬 The CGI - Cinema Recommendation System

**The CGI** is a full-stack movie recommendation website designed to help users discover their next favorite film through a dynamic and user-friendly interface. Developed and deployed locally, this platform allows users to explore movies, watch trailers, and stay updated with the latest cinema news.

## ✨ Key Features

### 👤 User Features
* **Discovery Portal:** Browse "Top Picks", "Popular Movies", and "Latest News".
* **Media Interaction:** Watch and download movie trailers directly from the platform.
* **User Accounts:** Secure registration with email verification logic.

### 🛡️ Admin Features
* **Dashboard:** A comprehensive panel to manage movie data and news content.
* **Admin Management:** Existing admins can approve new admin registrations via the "Admin View" menu.

## 🛠️ Tech Stack
* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL (`movie_site`)
* **Environment:** XAMPP (Apache Server)

---

## 🚀 Installation & Setup

To run this project locally, please follow these steps strictly:

### 1. Configure the Environment
Ensure you have **XAMPP** (or a similar PHP/MySQL environment) installed.

### 2. Project Placement
Move the entire project folder named `412023037_Final Project` into your XAMPP `htdocs` directory.
> **Path Example:** `C:\xampp\htdocs\412023037_Final Project`

### 3. Database Setup
1.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Create a new database named:
    ```sql
    movie_site
    ```
3.  Import the SQL file provided in the project folder into this database.

### 4. Access the Website
Open your browser and visit the following URL:
[http://localhost/412023037_Final%20Project/](http://localhost/412023037_Final%20Project/)

---

## 🔐 Access Credentials & Usage Guide

### 👨‍💼 Admin Login
To access the Admin Dashboard, use the following pre-configured credentials.
> **Note:** New Admin sign-ups **MUST** be approved by an existing admin via the "Admin View" menu before they can log in.

* **Username:** `Dhixerz`
* **Password:** `Dhian1234`

### 👤 User Sign Up (Important!)
When registering a new User account:
1.  Fill in the registration form.
2.  You **MUST** click the **'Email Verification'** button/link provided during the process.
3.  The account will only be valid and active for login *after* this verification step is clicked.

---

## ⚠️ Known Limitations
* **Media Assets:** Due to file size constraints in the repository, some movies and shows may have mismatched trailers or low-resolution images. These act as placeholders for demonstration purposes.

---

## 📝 Author
**Dhian Juwita Putri**
*Full-Stack Web Development Project*
