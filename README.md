# 🌸 Lunara Spa - Complete Booking & E-Commerce System

<div align="center">

**A Full-Featured Spa Management & E-Commerce Platform**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![jQuery](https://img.shields.io/badge/jQuery-3.6-0769AD?style=for-the-badge&logo=jquery&logoColor=white)](https://jquery.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

![Educational](https://img.shields.io/badge/Purpose-Educational%20Only-orange?style=for-the-badge)
![Not For Commercial Use](https://img.shields.io/badge/Commercial%20Use-Not%20Allowed-red?style=for-the-badge)

**🚀 Tech Highlights:** Pure PHP (No Frameworks) • PDO Prepared Statements • FullCalendar v6 • ToyyibPay Integration • RESTful APIs • Session-Based Auth

[Demo](#-screenshots) • [Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation)

</div>

---

## 👨‍💻 About This Project

This comprehensive spa booking and e-commerce system was **developed entirely from scratch** as a solo project during my internship at **Picrust** (February 2024 - July 2024).

> **ℹ️ INSPIRATION:** This project was inspired by **Kapas Spa Beauty**. The **Lunara Spa logo and brand** were **created by me (Muhammad Ilyas Bin Amran)** as original work. All service category images are sourced from **Freepik**, **Unsplash**, and **Pexels.com** under their respective free licenses. Service names and categories have been modified to create a unique identity.

> **⚠️ DISCLAIMER:** This project is developed **for educational and learning purposes only**. This is a portfolio project to demonstrate technical skills and is not intended for commercial use.

### 🎯 The Challenge

I challenged myself to build a complete, production-ready web application **using pure PHP without any frameworks** to truly understand web development fundamentals and test my coding abilities.

### 💪 The Journey

- **Duration:** 4 months of intensive development
- **Solo Developer:** Muhammad Ilyas Bin Amran
- **Technology Choice:** Vanilla PHP (no Laravel, no CodeIgniter, no frameworks)
- **Learning Experience:** Deep dive into PHP fundamentals, security, architecture, and best practices
- **Outcome:** A fully functional, feature-rich system managing real business operations

> *"Building without frameworks taught me how frameworks actually work. Every feature, every security measure, every optimization - I built it myself and understood it completely."* 

---

## ✨ Features

### 👥 Customer Portal

- **🔐 User Authentication**
  - Secure registration and login system
  - Password encryption
  - Session management
  - Profile management
  
- **💆 Service Booking System**
  - Real-time availability checking
  - Interactive calendar interface (FullCalendar.js integration)
  - Multiple service categories:
    - Facial treatments
    - Body massage (Terapi Urutan)
    - Terapi Wap sessions
    - Foot treatments (Terapi Kaki )
    - Cupping therapy (Terapi Bekam)
    - Terapi Wax services
    - Scrub treatments
    - And 6 more specialized services
  - Dynamic time slot management
  - Booking confirmation system

- **🛒 E-Commerce Features**
  - Product catalog with image galleries
  - Shopping cart functionality
  - Wishlist system
  - Credit packages (top-up system)
  - Gift card purchasing
  - Promotional packages
  - Order tracking
  - Invoice generation

- **💳 Payment Integration**
  - ToyyibPay payment gateway
  - Secure transaction handling
  - Credit system for spa services
  - Order history

- **📱 User Experience**
  - Responsive design (mobile, tablet, desktop)
  - SweetAlert2 notifications
  - Swiper.js image sliders
  - Real-time search functionality
  - Category filtering
  - Quick view product previews

### 👨‍💼 Admin Panel

- **📊 Dashboard**
  - Real-time statistics
  - Revenue tracking
  - Booking analytics
  - Customer insights

- **📅 Booking Management**
  - View all bookings
  - Calendar view
  - Booking status updates
  - Customer booking history
  - Time slot management

- **🛍️ Product Management**
  - CRUD operations for products
  - Image upload (up to 3 images per product)
  - Stock management
  - Price management
  - Product categorization

- **💼 Service Management**
  - Service catalog management
  - Duration settings
  - Pricing configuration
  - Service categorization

- **🎁 Gift Card & Credit Management**
  - Gift card creation and tracking
  - Credit package management
  - Transaction history

- **👥 User Management**
  - Customer accounts management
  - Staff accounts management
  - Admin accounts management
  - Role-based access control

- **📧 Communication**
  - Customer messaging system
  - Notifications management

- **📈 Reports & Analytics**
  - Order reports
  - Booking records
  - Revenue reports

### 👨‍⚕️ Staff Panel

- **📋 Booking Overview**
  - Assigned appointments
  - Daily schedule
  - Calendar integration

- **👤 Client Management**
  - View client details
  - Booking history
  - Client notes

- **💰 Financial Management**
  - Credit tracking
  - Service pricing
  - Gift card validation

- **🔧 Profile Management**
  - Personal information
  - Password change
  - Work schedule

---

## 🔧 Why the Booking System Took 1 Month to Build

### The Challenge

The booking system looks simple - just pick a date and time, right? Wrong. It's the **hardest part** of this project and took almost **1 month** to get right.

---

### 💡 Why It's So Hard

#### 1. **Time Overlap Logic**

You need to catch EVERY way two bookings can overlap:

```
Case 1: New starts during existing    Case 2: New ends during existing
Existing:  |-------|                  Existing:      |-------|
New:           |-------|              New:       |-------|

Case 3: New wraps existing            Case 4: Existing wraps new
Existing:    |---|                    Existing:  |---------|
New:       |---------|                New:         |---|
```

**The fix:**
```php
// One simple check catches all cases
if ($newStart < $existingEnd && $newEnd > $existingStart) {
    // They overlap!
}
```

My first try missed some cases and let therapists get double-booked.

---

#### 2. **Different Service Times**

- 60-min service at 10:00 ends at 11:00
- 90-min service at 10:30 ends at 12:00  
- These overlap but have different time strings!

**My mistake:** I checked if time strings matched like "10:00 - 11:30 AM". This missed overlaps with different durations.

**The fix:** Compare actual start/end times, not text strings.

---

#### 3. **3 Different Interfaces = 3× Work**

Every feature needs to work in:
- Public booking page
- Staff booking page  
- Admin booking page

Every bug fix = update 6+ files to stay consistent.

---

#### 4. **Database Problems**

Had to handle:
- Two users booking same slot at the same time
- Cancelled bookings still blocking slots (big bug!)
- Payment failures mid-booking
- Making sure all time data matches up

One mistake = lost bookings or double-bookings.

---

#### 5. **Real-Time Updates**

```javascript
// Page updates without refresh when:
// - Date changes
// - Staff changes
// - Service changes (different durations!)
```

Keeping green/red buttons accurate was tricky.

---

#### 6. **Payment System**

ToyyibPay integration needs:
- Create booking ID before payment
- Handle payment success/failure
- Sync payment status with booking status

Bugs here = lost money or angry customers.

---

### 📊 Time Breakdown (1 Month Total)

| Task | Days | Why It Took Long |
|------|------|------------------|
| Booking form & UI | 3-4 | Date picker, dropdowns, AJAX |
| Time slot generator | 2-3 | Loop logic, formatting |
| Overlap detection | 2 | First version had bugs |
| Database setup | 1 | Getting structure right |
| Payment system | 4-5 | ToyyibPay API integration |
| Staff/Admin pages | 3-4 | Copy logic for 3 interfaces |
| Bug fixes | 5-7 | Finding and fixing issues |
| Testing | 5-6 | Testing 100+ scenarios |
| **Total** | **25-30 days** | **Almost 1 month!** |

---

### 🔨 Main Bugs I Fixed

**Bug 1: Bad Overlap Check**
- Missed some overlap cases
- Let therapists get double-booked
- Fixed with better math formula

**Bug 2: Cancelled Bookings Block Slots**
- Cancelled appointments still showed as busy
- Fixed by filtering them out:
```php
WHERE bookingstat != 'Dibatalkan' AND bookingstat != 'Cancelled'
```

**Bug 3: Comparing Text Instead of Times**
- Only checked if time strings matched exactly
- Example: "10:00-11:30 AM" vs "10:30-12:00 PM" 
- These overlap but are different strings!
- Fixed by comparing actual times

**Bug 4: Different Duration Problems**
- 60-min and 90-min services could overlap
- Fixed by Bug 3's time comparison

---

### 🎯 What I Learned

This "simple" booking form taught me:
- Math matters (overlap logic)
- Database race conditions
- Why testing takes so long
- How to keep code consistent across multiple files
- Real production problems

**Bottom line:** Booking systems look easy but aren't. Even big companies get these bugs. Taking 1 month for this is normal when building from scratch.

---

## 💻 Technologies, Frameworks & Tools

### Backend
- **PHP 8.x** - Server-side logic using both Procedural & OOP paradigms
- **MySQL 8.0** - Relational database management
- **PDO (PHP Data Objects)** - Secure database operations with prepared statements
- **Session-based Authentication** - User authorization and access control
- **RESTful API Endpoints** - Custom AJAX endpoints for dynamic functionality
- **cURL** - HTTP client for API communication

### Frontend
- **HTML5** - Semantic markup and modern web standards
- **CSS3** - Advanced styling with custom properties
  - CSS Variables for dynamic theming
  - Custom animations and transitions
  - Mobile-first responsive design
- **JavaScript (ES6+)** - Modern client-side programming
  - Arrow functions, async/await
  - Template literals
  - Modules and classes
- **Bootstrap 5** - Responsive UI framework for consistent components

### JavaScript Libraries & Plugins
- **jQuery** - Simplified DOM manipulation and AJAX requests
- **FullCalendar v6** - Interactive booking calendar visualization with drag-and-drop
- **SweetAlert2** - Modern, customizable alert and confirmation dialogs
- **Flatpickr** - Advanced date/time picker with Malay localization support
- **Swiper.js** - Touch-enabled, mobile-friendly product image sliders
- **Moment.js** - Date/time manipulation, parsing, and formatting
- **DataTables** - Advanced table features with:
  - Real-time sorting and filtering
  - Pagination
  - Search functionality
  - Export capabilities
- **Font Awesome 6** - Comprehensive icon library (1000+ icons)

### Third-Party Integration
- **ToyyibPay Payment Gateway** - Secure payment processing for Malaysian market
  - Online banking (FPX)
  - Credit/debit card support
  - E-wallet integration
- **cURL** - API communication and HTTP requests

### Development Tools & Environment
- **XAMPP** - Cross-platform development stack
  - Apache Web Server
  - MySQL Database Server
  - PHP Interpreter
  - phpMyAdmin for database management
- **Git** - Version control and collaborative development
- **JSON** - Data serialization for API responses and configuration

### Security Features
- **Environment Variable Management** - Secure credential storage (.env)
- **SQL Injection Prevention** - PDO prepared statements throughout
- **XSS Protection** - `htmlspecialchars()` and input sanitization
- **Session Security** - Secure session handling and regeneration
- **Password Hashing** - Encrypted password storage
- **Input Validation** - Server-side and client-side validation
- **CSRF Protection** - Token-based form security (recommended for future enhancement)

---

### API Endpoints (AJAX)

```php
// Booking System APIs
GET  /getTimeslot.php              // Fetch available time slots
GET  /getDuration.php              // Get service duration
POST /getBookingInsertion.php      // Create new booking
GET  /getInitialTimeslot.php       // Load initial calendar data

// Admin Panel APIs
GET  /admin/ajax-viewclient.php    // Client details modal
GET  /admin/ajax-viewstaff.php     // Staff details modal
POST /admin/insert-data.php        // Insert records
POST /admin/update-data.php        // Update records

// Shopping Cart APIs
POST /components/wishlist_cart.php // Add to cart/wishlist
```

---

## 📋 System Requirements

- **PHP:** 8.0 or higher
- **MySQL:** 8.0 or higher
- **Apache/Nginx:** Latest stable version
- **Web Server:** XAMPP, WAMP, LAMP, or LEMP stack
- **Browser:** Modern browsers (Chrome, Firefox, Safari, Edge)

---

## 🚀 Installation

### 1️⃣ Clone or Download

```bash
# Clone the repository
git clone https://github.com/yourusername/lunaraspa.com.git

# Or download and extract the ZIP file
```

### 2️⃣ Setup Environment File

```bash
# Windows Command Prompt
copy env.template .env

# Windows PowerShell
Copy-Item env.template .env

# Linux/Mac
cp env.template .env
```

### 3️⃣ Configure Database

Edit the `.env` file:

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=spa_db
DB_USER=root
DB_PASS=

# ToyyibPay API (update with your credentials)
TOYYIBPAY_SECRET_KEY=your_secret_key_here
TOYYIBPAY_CATEGORY_CODE=your_category_code_here

# Application Settings
APP_ENV=development
APP_DEBUG=true
```

### 4️⃣ Import Database

1. Start your MySQL server
2. Create a database named `spa_db`
3. Import the database schema:
   - Access phpMyAdmin: `http://localhost/phpmyadmin`
   - Select the `spa_db` database
   - Import the SQL file (if provided) or create tables manually

### 5️⃣ Configure Web Server

#### For XAMPP:
1. Move the project to `C:\xampp\htdocs\`
2. Start Apache and MySQL from XAMPP Control Panel
3. Access the application at: `http://localhost/lunaraspa.com/`

### 6️⃣ Create Required Directories

Both image folders are excluded from Git, so you need to create them and add your own images.

```bash
# Create the required folders

# Windows Command Prompt
mkdir uploaded_img
mkdir images

# Windows PowerShell
New-Item -ItemType Directory -Force -Path uploaded_img
New-Item -ItemType Directory -Force -Path images

# Linux/Mac
mkdir -p uploaded_img images
```

**Set proper permissions** (Linux/Mac only):
```bash
chmod 755 uploaded_img images
```

#### 📁 Images Folder Setup

The `images/` folder contains static UI images. You need to add the following files:

**Required Logo & Icons:**
- `lunara-new-logo.png` - Main spa logo (displayed in header and README)

**Service Category Images (14 files):**
You need 14 service category images named `sc1.png` through `sc14.png`:
- `sc1.png` - Facial treatment image
- `sc2.png` - Body massage image  
- `sc3.png` - Wap therapy image
- `sc4.png` - Foot treatment image
- `sc5.png` - Cupping therapy image
- `sc6.png` - Waxing service image
- `sc7.png` - Scrub treatment image
- `sc8.png` - Service category 8
- `sc9.png` - Service category 9
- `sc10.png` - Service category 10
- `sc11.png` - Service category 11
- `sc12.png` - Service category 12
- `sc13.png` - Service category 13
- `sc14.png` - Service category 14

**Where to get images:**
- 📸 **Freepik** - https://www.freepik.com (Free License)
- 📸 **Unsplash** - https://unsplash.com (Free for commercial use)
- 📸 **Pexels** - https://www.pexels.com (Free License)

**Image recommendations:**
- Format: PNG or JPG
- Size: 800x600px or similar (will be auto-resized)
- Quality: High resolution for clarity

#### 📦 Uploaded_img Folder

The `uploaded_img/` folder is for user-uploaded content (product images, profile pictures) managed through the admin panel.

> **Note:** Both folders are excluded from version control via `.gitignore` to keep the repository size small and prevent licensing issues. You must add your own images after installation.

### 7️⃣ Access the Application

- **Main Website:** `http://localhost/lunaraspa.com/`
- **Admin Panel:** `http://localhost/lunaraspa.com/admin/`
- **Staff Panel:** `http://localhost/lunaraspa.com/staff/`

---

## 📁 Project Structure

```
lunaraspa.com/
├── 📂 admin/                    # Admin panel files
│   ├── dashboard.php           # Admin dashboard
│   ├── booking.php             # Booking management
│   ├── services.php            # Service management
│   ├── products.php            # Product management
│   ├── client-accounts.php     # Customer management
│   └── ...
├── 📂 staff/                    # Staff panel files
│   ├── dashboard.php           # Staff dashboard
│   ├── booking.php             # View appointments
│   ├── calendar.php            # Calendar view
│   └── ...
├── 📂 components/               # Reusable components
│   ├── connect.php             # Database connection
│   ├── env_loader.php          # Environment loader
│   ├── user-header.php         # User header
│   ├── admin_header.php        # Admin header
│   └── ...
├── 📂 css/                      # Stylesheets
│   ├── style.css               # Main styles
│   ├── admin_style.css         # Admin styles
│   └── staff_style.css         # Staff styles
├── 📂 js/                       # JavaScript files
│   ├── script.js               # Main scripts
│   ├── admin_script.js         # Admin scripts
│   └── sweetalert2.all.min.js  # SweetAlert2
├── 📂 images/                   # Static images
├── 📂 uploaded_img/             # User uploaded images
├── 📂 font/                     # Custom fonts (Gilroy)
├── index.php                    # Homepage
├── booking.php                  # Booking page
├── shop.php                     # Shop page
├── cart.php                     # Shopping cart
├── user-login.php               # Customer login
├── user-register.php            # Customer registration
├── .env                         # Environment variables (create from template)
├── env.template                 # Environment template
└── README.md                    # This file
```

---

## 🔒 Security Features

### Implemented Security Measures

1. **SQL Injection Prevention**
   - All database queries use PDO prepared statements
   - No raw SQL queries with user input

2. **Cross-Site Scripting (XSS) Protection**
   - All user inputs sanitized with `htmlspecialchars()`
   - Output escaping on all dynamic content

3. **Session Security**
   - Secure session management
   - Session regeneration on login
   - Automatic logout on inactivity

4. **Environment Variables**
   - Sensitive data stored in `.env` file
   - `.env` excluded from version control
   - Easy configuration management

5. **Password Security**
   - Password hashing (recommended to upgrade to password_hash())
   - No plain text storage

6. **Input Validation**
   - Server-side validation on all forms
   - Type checking and sanitization
   - Maximum length enforcement


---

## 📖 Documentation

### Database Schema

The system uses the following main tables:

- **clients** - Customer information
- **admins** - Administrator accounts
- **staff** - Staff accounts
- **services** - Spa services catalog
- **products** - E-commerce products
- **giftcards** - Gift card products
- **credits** - Credit packages
- **bookings** - Service bookings
- **orders** - Product orders
- **cart** - Shopping cart items
- **wishlist** - Customer wishlists
- **messages** - Customer messages

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `DB_HOST` | Database host | `localhost` |
| `DB_NAME` | Database name | `spa_db` |
| `DB_USER` | Database username | `root` |
| `DB_PASS` | Database password | _(empty)_ |
| `TOYYIBPAY_SECRET_KEY` | Payment gateway secret | _(your key)_ |
| `TOYYIBPAY_CATEGORY_CODE` | Payment category code | _(your code)_ |
| `APP_ENV` | Environment mode | `development` |
| `APP_DEBUG` | Debug mode | `true` |

### Image Folders

This project uses two image directories:

| Folder | Purpose | Git Tracked? | Action Needed |
|--------|---------|--------------|---------------|
| `images/` | Static UI images, logos, service categories | ❌ No | Create folder & add 15 images (see step 6) |
| `uploaded_img/` | User-uploaded content (products, profiles) | ❌ No | Create folder & add content via admin panel |

> **Important:** Both folders are **excluded from GitHub**. After cloning, you MUST create these folders and add your own images (see [Installation Step 6](#6%EF%B8%8F⃣-create-required-directories) for the complete list and naming guide).

### Image Sources & Recommendations

**Where to get free images:**
- 📸 **Freepik** - https://www.freepik.com (Free License)
- 📸 **Unsplash** - https://unsplash.com (Free for commercial use)
- 📸 **Pexels** - https://www.pexels.com (Free License)

**Logo:**
- Create your own spa logo or hire a designer
- Name it: `lunara-new-logo.png`

**Service Categories:**
- Search for spa-related images (massage, facial, therapy, etc.)
- Name them: `sc1.png`, `sc2.png`, ... `sc14.png`

---




## 🤝 Contributing

This project was built as a solo internship project, but contributions are welcome!

### How to Contribute

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## 🐛 Bug Reports

Found a bug? Please open an issue with:
- Description of the bug
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Environment details (PHP version, browser, OS)

---

## 📜 License & Disclaimer

### Educational Use License

This project is developed for **educational and learning purposes only** as part of an internship project.

**Code License:** The source code is available under the MIT License for educational reference.

### ⚠️ Important Copyright Notice

**Brand & Assets Attribution:**
- **Inspiration:** This project was inspired by Kapas Spa Beauty's business model
- **Logo & Brand:** The Lunara Spa logo and brand identity were **created by Muhammad Ilyas Bin Amran** as original work
- **Service Images:** All service category images are sourced from:
  - **Freepik** (https://www.freepik.com) - Free images under Freepik License
  - **Unsplash** (https://unsplash.com) - Free images under Unsplash License
  - **Pexels** (https://www.pexels.com) - Free images under Pexels License
- **Service Names:** All service names and categories have been modified and customized
- **ToyyibPay:** The ToyyibPay brand and API belong to ToyyibPay Sdn Bhd
- This project uses these materials **strictly for educational demonstration purposes**

### Usage Restrictions

- ✅ **Allowed:** Use the code for learning, study, and portfolio purposes
- ✅ **Allowed:** Reference the architecture and implementation patterns
- ✅ **Allowed:** Fork and modify for personal educational projects
- ✅ **Allowed:** Use of images from Freepik, Unsplash, and Pexels per their respective licenses
- ❌ **NOT Allowed:** Commercial use without proper licensing
- ❌ **NOT Allowed:** Use of Lunara Spa brand and logo without permission from creator
- ❌ **NOT Allowed:** Violating Freepik, Unsplash, or Pexels license terms

### Fair Use Statement

This project is created as a **portfolio demonstration** and **educational case study** to showcase:
- Web development skills
- Full-stack PHP programming
- System architecture design
- Problem-solving abilities

**If you are the copyright holder** of any assets used in this project and would like them removed, please contact me immediately.

---

### MIT License (Code Only)

```
MIT License

Copyright (c) 2024 Muhammad Ilyas Bin Amran

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

**Note:** This license applies to the source code only. Third-party libraries, logos, images, and brand materials retain their original licenses and copyrights.

---

## 👤 Author

**Muhammad Ilyas Bin Amran**

- 📧 Email: [muhammadilyasamran@gmail.com]
- 💻 GitHub: [@unatesta175](https://github.com/unatesta175)
- 🔗 LinkedIn: [MUHAMMAD ILYAS BIN AMRAN](https://linkedin.com/in/muhammad-ilyas-bin-amran-3a9a2a298)

---


### Special Thanks
- **Picrust** for the internship opportunity
- The PHP community for extensive documentation
- Stack Overflow for debugging help
- All the open-source library authors

---

## 📞 Support

Need help? Have questions?

- 📧 Email: muhammadilyasamran.com
- 💬 Issues: [GitHub Issues](https://github.com/unatesta175/lunaraspa.com/issues)
- 📖 Docs: [Read the Setup Guide](README_SETUP.md)

---

## 💰 Hire Me

Looking for a developer who can build complex systems from scratch without relying on frameworks?

I've proven I can:
- ✅ Build production-ready applications solo
- ✅ Learn and implement new technologies quickly
- ✅ Write clean, maintainable code
- ✅ Understand systems at a fundamental level
- ✅ Deliver complete projects on time

**Available for:**
- Freelance projects
- Full-time positions
- Contract work
- Consulting

Contact: [muhammadilyasamran.com]

---

<div align="center">

### ⭐ If you found this project interesting, please consider giving it a star!

**Built with 💪 and pure PHP by Muhammad Ilyas Bin Amran**

*4 months of coding, debugging, and learning - all to prove I could do it without frameworks.*

---

![Made with PHP](https://img.shields.io/badge/Made%20with-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Made with Love](https://img.shields.io/badge/Made%20with-Love-red?style=for-the-badge&logo=heart&logoColor=white)
![No Frameworks](https://img.shields.io/badge/No-Frameworks-success?style=for-the-badge&logo=checkmarx&logoColor=white)
![Educational Purpose](https://img.shields.io/badge/Educational-Purpose%20Only-orange?style=for-the-badge&logo=graduation-cap&logoColor=white)

**© 2024 Muhammad Ilyas Bin Amran**

*Educational Portfolio Project • Inspired by Kapas Spa Beauty • Logo & Brand Created by Developer • Images from Freepik, Unsplash & Pexels • For Learning Purposes Only*

</div>

