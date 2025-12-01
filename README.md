# SafeCity Crime Reporting System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2.3-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

## 📌 Overview
SafeCity is a comprehensive web-based platform designed to bridge the gap between citizens and law enforcement agencies. The system facilitates efficient crime reporting, case management, and data analysis to enhance public safety and community engagement.

## ✨ Features

### For Citizens
- 🚨 **Incident Reporting**: User-friendly interface for reporting crimes with location mapping
- 🔔 **Real-time Updates**: Track your report status in real-time
- 🕵️ **Anonymous Reporting**: Option to submit reports confidentially
- 📊 **Dashboard**: Personal dashboard to view and manage your reports
- 📱 **Responsive Design**: Accessible on all devices

### For Law Enforcement
- 🛡️ **Case Management**: Comprehensive tools for handling and assigning cases
- 📈 **Analytics Dashboard**: Interactive data visualization for crime pattern analysis
- 👥 **User Management**: Secure authentication with role-based access control
- 📑 **Documentation**: Automated report generation and evidence tracking

## 🛠️ Tech Stack

### Backend
- PHP 8.1+
- Laravel 10.x
- MySQL/PostgreSQL

### Frontend
- HTML5, CSS3, JavaScript (ES6+)
- Bootstrap 5.2.3
- Chart.js for data visualization
- Vite for asset bundling

### Additional Tools
- Laravel Echo for real-time features
- Pusher for WebSockets
- Composer for dependency management
- Git for version control

## 🚀 Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/safecity-crime-reporting.git
   cd safecity-crime-reporting
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Update your `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=safecity
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compile assets**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   Open your browser and visit: `http://localhost:8000`

## 📸 Screenshots

| Feature | Screenshot |
|---------|------------|
| Login Page | ![Login Page](/public/images/screenshots/login.png) |
| Dashboard | ![Dashboard](/public/images/screenshots/dashboard.png) |
| Incident Report | ![Report Form](/public/images/screenshots/report-form.png) |
| Admin Panel | ![Admin Panel](/public/images/screenshots/admin-panel.png) |

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👏 Acknowledgments

- [Laravel](https://laravel.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Chart.js](https://www.chartjs.org/)
- All contributors who have helped shape this project

---

<div align="center">
  Made with ❤️ by [Your Name]
</div>