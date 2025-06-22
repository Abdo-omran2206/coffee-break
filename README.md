# ☕ Coffee Break - Cozy Coffee Shop Website

<div align="center">
  <img src="coffee-icon.svg" alt="Coffee Break Logo" width="100" height="100">
  <h3>Coffee. Comfort. Community.</h3>
</div>

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Project Structure](#project-structure)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Admin Dashboard](#admin-dashboard)
- [Order Management](#order-management)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## 🌟 Overview

Coffee Break is a comprehensive coffee shop management system featuring a modern, responsive website with integrated ordering system, admin dashboard, and cashier management. Built with HTML5, CSS3, JavaScript, and PHP, it provides a complete solution for coffee shop operations.

## ✨ Features

### 🏠 Customer-Facing Features
- **Responsive Design**: Mobile-first approach with modern UI/UX
- **Dark/Light Theme Toggle**: Automatic theme detection with manual override
- **Interactive Menu**: Categorized menu with tabs (Hot Drinks, Cold Brews, Pastries, Specials)
- **Online Ordering System**: Real-time cart management with search functionality
- **Gallery Section**: Beautiful image showcase
- **Contact Form**: Customer communication interface
- **Smooth Animations**: CSS animations and scroll effects

### 🛒 Ordering System
- **Product Catalog**: 30+ coffee drinks with descriptions and pricing
- **Shopping Cart**: Add/remove items with quantity management
- **Search Functionality**: Real-time product search
- **Order Tracking**: Unique order keys for status tracking
- **Responsive Cart**: Mobile-optimized cart interface

### 👨‍💼 Admin Dashboard
- **Dashboard Overview**: Real-time statistics and analytics
- **Order Management**: View, update, and manage customer orders
- **Product Management**: Add, edit, and remove menu items
- **Customer Database**: Customer information and order history
- **Analytics**: Sales reports and performance metrics
- **Theme Toggle**: Dark/light mode for admin interface

### 💰 Cashier Management
- **Order Processing**: Real-time order status updates
- **Payment Tracking**: Order completion and payment status
- **User Management**: Customer order history and management
- **Status Filtering**: Filter orders by status (pending, processing, done, cancelled)

## 📁 Project Structure

```
coffee/
├── 📄 index.html                 # Main website homepage
├── 📄 main.js                    # Main JavaScript functionality
├── 📄 style.css                  # Main stylesheet
├── ☕ coffee-icon.svg            # Project icon
├── 📁 css/                       # CSS modules
│   ├── animations.css
│   ├── contact.css
│   ├── gallery.css
│   ├── header.css
│   ├── menu.css
│   └── sections.css
├── 📁 orders/                    # Ordering system
│   ├── order.html               # Order page
│   ├── main.js                  # Order functionality
│   ├── 📁 css/                  # Order-specific styles
│   ├── 📁 js/                   # Order JavaScript modules
│   └── 📁 php/                   # Database files
│       └── get-drinks           # get drinks from db
├── 📁 admin/                     # Admin dashboard
│   ├── admin.html               # Admin interface
│   ├── admin.js                 # Admin functionality
│   ├── 📁 js/                   # Admin JavaScript modules
│   ├── 📁 myphp/                # PHP backend
│   └── 📁 style/                # Admin styles
├── 📁 casher/                    # Cashier system
│   ├── casher.php               # Order processing
│   └── cheker.php               # Order verification
└── 📁 casher_man/               # Cashier management
    ├── index.html               # Management interface
    ├── script.js                # Management functionality
    └── style.css                # Management styles
```

## 🛠️ Technologies Used

### Frontend
- **HTML5**: Semantic markup and structure
- **CSS3**: Modern styling with Flexbox and Grid
- **JavaScript (ES6+)**: Interactive functionality and DOM manipulation
- **Font Awesome**: Icon library
- **Google Fonts**: Typography (Playfair Display, Poppins)

### Backend
- **PHP**: Server-side processing and database operations
- **MySQL**: Database management
- **jQuery**: DOM manipulation and AJAX requests
- **SweetAlert2**: User-friendly notifications

### Libraries & Tools
- **Chart.js**: Analytics and data visualization
- **Responsive Design**: Mobile-first approach
- **Local Storage**: Theme persistence and user preferences

## 🚀 Installation

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/coffee-break.git
   cd coffee-break
   ```

2. **Database Setup**
   ```sql
   CREATE DATABASE coffee-break;
   USE coffee-break;
   ```

3. **Configure Database Connection**
   - Edit `casher/casher.php` and `admin/myphp/` files
   - Update database credentials:
     ```php
     $db = new mysqli('localhost', 'username', 'password', 'coffee-break');
     ```

4. **Web Server Configuration**
   - Place files in your web server directory
   - Ensure PHP is enabled
   - Set proper file permissions

5. **Access the Application**
   - Main website: `http://localhost/coffee/`
   - Admin dashboard: `http://localhost/coffee/admin/`
   - Order system: `http://localhost/coffee/orders/`
   - Cashier management: `http://localhost/coffee/casher_man/`

## 📖 Usage

### For Customers
1. **Browse Menu**: Visit the homepage to explore coffee options
2. **Place Orders**: Navigate to the order page to add items to cart
3. **Track Orders**: Use the unique order key to check status
4. **Contact Support**: Use the contact form for inquiries

### For Administrators
1. **Access Dashboard**: Login to admin panel
2. **Manage Orders**: View and update order statuses
3. **Update Menu**: Add, edit, or remove products
4. **View Analytics**: Monitor sales and performance metrics

### For Cashiers
1. **Process Orders**: Update order status in real-time
2. **Manage Payments**: Track payment completion
3. **Customer Service**: Access customer order history

## 🔧 Admin Dashboard

The admin dashboard provides comprehensive management tools:

- **Dashboard Overview**: Real-time statistics
- **Order Management**: Complete order lifecycle management
- **Product Catalog**: Menu item management
- **Customer Database**: Customer information and history
- **Analytics**: Sales reports and performance metrics

## 🛒 Order Management

The ordering system features:

- **Product Catalog**: 30+ coffee drinks with detailed descriptions
- **Shopping Cart**: Real-time cart management
- **Order Tracking**: Unique keys for order status
- **Search & Filter**: Easy product discovery
- **Responsive Design**: Mobile-optimized interface

## 🔌 API Endpoints

### Order Processing
- `POST /casher/casher.php` - Process new orders
- `POST /casher/cheker.php` - Verify order status

### Admin Operations
- `GET /admin/myphp/orders.php` - Retrieve orders
- `POST /admin/myphp/orders_actions.php` - Update order status
- `GET /admin/myphp/products.php` - Manage products
- `GET /admin/myphp/customers.php` - Customer data

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines
- Follow existing code style and conventions
- Test thoroughly on multiple devices and browsers
- Update documentation for new features
- Ensure responsive design compatibility

## 🙏 Acknowledgments

- Coffee shop imagery from [Pexels](https://www.pexels.com/)
- Icons from [Font Awesome](https://fontawesome.com/)
- Typography from [Google Fonts](https://fonts.google.com/)
- UI components inspired by modern coffee shop designs

---

<div align="center">
  <p>Made with ☕ and ❤️ for coffee lovers everywhere</p>
  <p><strong>Coffee Break</strong> - Where every cup tells a story</p>
</div>
