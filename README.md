# 🏪 Supermarket Staff Management System

A comprehensive Employee & Payroll Management System built with Laravel 11, designed for supermarkets and retail businesses to manage their workforce efficiently.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)

---

## ✨ Features

### 👥 Employee Management
- Complete employee profiles with personal, employment, and bank details
- Department and position assignment
- Employment status tracking (Active, On Leave, Terminated)
- Employee self-service portal

### ⏰ Attendance Tracking
- Clock in/out functionality for employees
- Shift-based attendance recording
- Real-time attendance monitoring
- Late arrival and early departure tracking
- Overtime calculation

### 📅 Shift Management
- Create and manage multiple shifts (Morning, Day, Evening, Night)
- Weekly shift scheduling with calendar view
- Bulk employee assignment to shifts
- Employee shift visibility

### 💰 Payroll Processing
- Automated salary calculation
- Kenya statutory deductions (NSSF, SHIF, PAYE)
- Configurable tax brackets and rates
- PDF payslip generation
- Payroll reversal capability
- Employee payslip access

### 📊 Reports & Analytics
- Attendance reports by department
- Performance metrics (attendance rate, punctuality)
- Payroll summaries
- Interactive dashboard with charts

### ⚙️ System Configuration
- Statutory settings management (NSSF, SHIF, PAYE rates)
- Department and position management
- Role-based access control (Admin, HR, Supervisor, Employee)

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Backend** | Laravel 11.x, PHP 8.2+ |
| **Frontend** | Blade Templates, Bootstrap 5.3 |
| **Database** | MySQL 8.0 |
| **Charts** | ApexCharts |
| **Tables** | DataTables |
| **PDF** | DomPDF |
| **Icons** | Font Awesome 6 |
| **Fonts** | Google Fonts (Inter) |

---

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (optional, for asset compilation)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Ngumbaujjdev/supermarket-staff-system.git
cd supermarket-staff-system
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supermarket_staff
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
# Create tables and seed sample data
php artisan migrate:fresh --seed
```

### 6. Start the Server

```bash
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## 🔐 Default Login Credentials

### Admin Account
| Email | Password | Role |
|-------|----------|------|
| `admin@supermarket.com` | `admin123` | System Admin |

### Employee Accounts
All employees use password: `password`

| Employee | Email | Position |
|----------|-------|----------|
| Ann Chebet | ann.chebet@manimo.co.ke | Cashier |
| John Mwangi | john.mwangi@manimo.co.ke | Stock Clerk |
| Grace Wanjiku | grace.wanjiku@manimo.co.ke | Sales Associate |
| Sarah Muthoni | sarah.muthoni@manimo.co.ke | HR Officer |
| Peter Ochieng | peter.ochieng@manimo.co.ke | Security Guard |

*...and 25 more employees*

---

## 📁 Project Structure

```
supermarket-staff-system/
├── app/
│   ├── Http/Controllers/      # Application controllers
│   ├── Models/                # Eloquent models
│   └── Services/              # Business logic services
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Data seeders
├── resources/
│   └── views/                 # Blade templates
│       ├── attendance/        # Attendance views
│       ├── dashboard/         # Dashboard views
│       ├── employees/         # Employee management
│       ├── payroll/           # Payroll views
│       ├── reports/           # Report views
│       ├── shifts/            # Shift management
│       └── layouts/           # Layout templates
├── routes/
│   └── web.php                # Web routes
└── public/
    └── css/custom.css         # Custom styles
```

---

## 🎯 Key Modules

### Dashboard
- Overview statistics (employees, attendance, payroll)
- Attendance trend charts
- Department distribution
- Recent activities feed

### Employees
- CRUD operations
- Auto-generated employee numbers
- Position-based salary suggestions
- Department filtering

### Attendance
- Manual attendance recording
- Self clock-in/out for employees
- Shift-linked attendance
- Status tracking (Present, Late, Absent, Leave)

### Payroll
- Monthly payroll processing
- Automatic statutory deductions
- PDF payslip generation
- Payroll reversal

### Shifts
- Shift definition (name, hours, times)
- Weekly schedule management
- Multi-employee assignment
- Employee schedule view

---

## 🇰🇪 Kenya Statutory Deductions

The system implements Kenyan tax regulations:

### NSSF (National Social Security Fund)
- Tier I: 6% of first KES 7,000
- Tier II: 6% of next KES 29,000

### SHIF (Social Health Insurance Fund)
- 2.75% of gross salary

### PAYE (Pay As You Earn)
Progressive tax rates:
- Up to KES 24,000: 10%
- KES 24,001 - 32,333: 25%
- KES 32,334 - 500,000: 30%
- KES 500,001 - 800,000: 32.5%
- Above KES 800,000: 35%

---

## 🔧 Configuration

### Statutory Settings
Navigate to **Settings** in the admin panel to configure:
- NSSF rates and thresholds
- SHIF rates
- PAYE tax brackets
- Personal relief amount

---

## 📱 User Roles

| Role | Access Level |
|------|--------------|
| **Admin** | Full system access |
| **HR** | Employee, payroll, attendance management |
| **Supervisor** | Attendance management only |
| **Employee** | Personal dashboard, payslips, schedule |

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Ngumbau JJ Dev**

- GitHub: [@Ngumbaujjdev](https://github.com/Ngumbaujjdev)

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap Team
- ApexCharts
- Font Awesome

---

<p align="center">
  Made with ❤️ in Kenya 🇰🇪
</p>
