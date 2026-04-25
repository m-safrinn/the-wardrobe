# 👗 The Wardrobe — Fashion Ecommerce Website

A full-stack fashion ecommerce web application built with PHP, MySQL, and HTML/CSS/JS — featuring a complete shopping experience from browsing to checkout, with order tracking, product reviews, and a powerful admin dashboard.

---

## 📌 Overview

The Wardrobe is a fully functional fashion ecommerce platform designed to provide a seamless online shopping experience. Customers can browse collections, manage their cart, place orders, and track deliveries — while admins have full control over products, orders, and customers through a dedicated dashboard.

---

## ✨ Features

### 🛍️ Customer Side
- 👤 User registration and login system
- 🛒 Shopping cart — add, remove and update items
- 💳 Checkout with order confirmation
- 📦 Order tracking — track your order status in real time
- ⭐ Product reviews and ratings
- 📧 Email confirmation after placing an order
- 👗 Browse collections and new arrivals
- 📋 Customer profile and order history

### 🔧 Admin Side
- 📊 Admin dashboard — manage products, orders and customers
- 📦 Update and manage order statuses
- 👥 View and manage registered customers
- 📈 Sales overview

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| Email | PHPMailer |
| Local Server | WAMP |

---

## 🚀 Getting Started

### Prerequisites
- WAMP / XAMPP installed on your machine
- PHP 7.4+
- MySQL

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/m-safrinn/the-wardrobe.git
```

2. **Move to your server directory**

Copy the project folder into:
```
C:\wamp64\www\
```

3. **Set up the database**
- Open **phpMyAdmin** at `http://localhost/phpmyadmin`
- Create a new database called `wardrobe`
- Click **Import** and select the `wardrobe.sql` file
- Click **Go** ✅

4. **Configure the database connection**

Open `Connection.php` and update if needed:
```php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = ''; // Add your database password here
$dbname = 'wardrobe';
```

5. **Run the project**

Open your browser and go to:
```
http://localhost/the-wardrobe/home.php
```

---

## 📁 Project Structure

```
the-wardrobe/
│
├── Admin dashboard/        # Admin panel pages
├── Shared/                 # Shared components (header, footer)
├── IMG/                    # Product images
├── phpmailer/              # PHPMailer library for email
│
├── home.php                # Homepage
├── product.php             # Product listing page
├── cart.php                # Shopping cart
├── checkout.php            # Checkout page
├── confirmation.php        # Order confirmation
├── login.php               # User login
├── register.php            # User registration
├── customer.php            # Customer profile
├── review.php              # Product reviews
├── Connection.php          # Database connection
├── wardrobe.sql            # Database export
└── ...
```

---

## 📸 Screenshots

> Screenshots coming soon

---

## 👨‍💻 Team

This project was built as a group project by:

| Name | 
|---|
| Mohamad Safrin |
| Raveen Sandeepa |
| Vidura Herath |
| Akesh Munasinghe |

---

## 📄 License

This project is intended for academic and educational purposes.
