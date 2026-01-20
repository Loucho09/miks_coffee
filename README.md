# ☕ Miks Coffee Shop

**Miks Coffee Shop** is a professional, full-stack E-commerce platform built with the **Laravel 12.x** framework. It features a robust role-based access control system, a dynamic loyalty program, and automated inventory management designed for a high-end cafe experience.



## 🌟 Key Features

### 🛒 Customer Experience
* **Dynamic Menu:** Browse products with real-time search and category filtering.
* **Smart Cart:** Handles quantity-based bulk discounts (10% off for 6+ items).
* **Loyalty Program:**
    * **Points System:** Earn +10 points per order and redeem points for discounts.
    * **Daily Streaks:** Automated streak tracking to reward frequent customers.
* **Referral System:** Earn +50 bonus points when a referred friend places their first order.
* **Support Tickets:** Integrated system for customers to send and track support requests.

### ☕ Barista Management (KDS)
* **Live Queue:** A real-time Kitchen Display System (KDS) to manage order fulfillment (Pending -> Preparing -> Ready -> Served).
* **Redemption Terminal:** Seamlessly verify and fulfill loyalty reward voucher claims.
* **Audio Notifications:** Instant sound alerts for new incoming orders.

### 📊 Admin Suite
* **Inventory Control:** Automated low-stock email alerts when items drop below 5 units.
* **Customer Management:** View profiles, reset passwords, and track performance tiers (Gold, Silver, Bronze).
* **Sales Analytics:** Export detailed CSV reports of sales performance and order history.

---

## 🛠️ Tech Stack

* **Backend:** Laravel 12.x & PHP 8.2+
* **Frontend:** Blade, Tailwind CSS, Alpine.js, and Vite
* **Environment:** Optimized for Windows using **Laragon** (WAMP)
* **Database:** MySQL (Local Development) or SQLite

---

## 🚀 Installation & Setup

This project is optimized for **Windows/Laragon** environments. Follow these steps in your PowerShell terminal:

### 1. Clone the repository
```powershell
git clone [https://github.com/Loucho/miks-coffee-shop.git](https://github.com/Loucho/miks-coffee-shop.git)
cd miks-coffee-shop

2. Install Dependencies
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install

3. Environment Setup
# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

4. Database Configuration
 * Open Laragon and click "Start All".
 * Create a new database named miks_coffee_shop in HeidiSQL.
 * Update your .env file with your database credentials.
 * Run migrations and seed data:
<!-- end list -->
php artisan migrate --seed

5. Compile Assets & Start
# Compile frontend assets
npm run dev

# The site is available at [http://miks-coffee-shop.test](http://miks-coffee-shop.test) (via Laragon)

📂 Core Logic Highlights
 * OrderController.php: Centralized logic for the transactional checkout process, including point calculations, referral bonuses, and stock updates.
 * QueueController.php: Powers the Barista Kitchen Display System with live updates and status transitions.
 * CheckRole.php: Custom middleware to ensure secure access between Customers, Baristas, and Admins.
🧹 Maintenance Commands
Use these commands to clear the cache during development if changes are not appearing:
# Clear all caches (Route, View, Config, and App Cache)
php artisan optimize:clear

👤 Author
Loucho
 * Role: Aspiring Full-Stack Developer & Technical Support Specialist
 * Location: Cavite, Philippines
 * Education: Computer Engineering Student
<!-- end list -->

---

### How to use this file
1. Open your project folder at `C:\Users\Loucho\miks-coffee-shop`.
2. Open the existing `README.md` file.
3. Select all the old text and delete it.
4. **Paste** the code block above into the file and save it.
