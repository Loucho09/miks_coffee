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
