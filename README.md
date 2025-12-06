# 🚗 Car Rental API

A backend REST API for a **Car Rental Service Web Application**, featuring user & admin authentication, car management, transactions, and reviews.

---

## 🛠️ Tech Stack
- Laravel (API Backend)
- MySQL Database
- Laravel Passport — API Authentication

---

## ✨ Key Features
- 👤 User & Admin Login / Register
- 📩 Email Verification support
- 🚘 Car CRUD & Branch (Cabang) Management
- 🧾 Rental Transaction System + Status Update
- ⭐ Car Review System
- 🔐 Protected routes using Bearer Token (Passport)

---

## 🚀 Getting Started

### 📌 Prerequisites
- PHP & Composer installed
- MySQL server
- Postman / API client for testing

### 🔧 Installation
```bash
composer install
cp .env.example .env
php artisan key:generate
```
### 🗄️ Database Setup
Update .env with your database credentials
```bash
php artisan migrate
php artisan passport:install
```
### ▶️ Run Server
```bash
php artisan serve
```
---

## 📍 API Endpoints
### 🔐 Public Routes
| Method | Endpoint          | Description       |
| ------ | ----------------- | ----------------- |
| POST   | `/register`       | Register new user |
| POST   | `/login`          | Login user        |
| POST   | `/admin/register` | Register admin (Demo purpose only)   |
| POST   | `/admin/login`    | Login admin       |
### 🔒 Protected Routes (auth:api required)
#### 🚘 Cars (Mobil)
- GET /mobil
- POST /mobil
- GET /mobil/{id}
- GET /mobil/cabang/{id}
- PATCH /mobil/{id}
- DELETE /mobil/{id}
#### 🏢 Branches (Cabang) 
- GET /cabang
- POST /cabang
- GET /cabang/{id}
- PUT /cabang/{id}
- DELETE /cabang/{id}
#### 🧾 Transactions (Transaksi)
- GET /transaksi
- POST /transaksi
- GET /transaksi/{id}
- GET /transaksi/status/{status}
- PATCH /transaksi/{id}
- DELETE /transaksi/{id}
#### ⭐ Reviews 
- GET /review
- POST /review
- GET /review/{id}
- GET /review/mobil/{id}
- PATCH /review/{id}
- DELETE /review/{id}
#### 👤 Users
- GET /user
- POST /user — Update Profile Picture
- GET /user/{id}
- PATCH /user/{id}
- DELETE /user/{id}
---
## 📄 License

This project is for educational purposes.
