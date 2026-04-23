# 🌍 Laravel 13 NGO Starter Kit

A production-ready Laravel 13 starter kit tailored for Non-Governmental Organizations (NGOs), charities, and non-profits. Get your mission-critical web platform up and running fast — with donor management, volunteer tracking, campaign tools, and role-based access baked in from day one.

---

## ✨ Features

- 🔐 **Authentication & RBAC** — Multi-role system (Admin, Staff, Volunteer, Donor) powered by Laravel Breeze + Spatie Permissions
- 💳 **Donor Management** — Track donors, donation history, and recurring giving
- 📣 **Campaign Manager** — Create and manage fundraising campaigns with goal tracking
- 🙋 **Volunteer Portal** — Volunteer registration, shift scheduling, and hour logging
- 📊 **Dashboard & Reports** — At-a-glance stats for donations, campaigns, and impact
- 📧 **Email Notifications** — Automated receipts, campaign updates, and newsletters via Laravel Mail
- 📁 **Media Library** — File and image uploads with Spatie Media Library
- 🌐 **Multi-language Ready** — Full i18n support out of the box
- 🌙 **Dark Mode** — Tailwind CSS dark mode support
- 🧪 **Test Suite** — Feature and unit tests with Pest PHP

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 |
| Frontend | Vite + Tailwind CSS 4 + Alpine.js |
| Auth Scaffolding | Laravel Breeze |
| Permissions | Spatie Laravel Permission |
| Media | Spatie Laravel Media Library |
| Testing | Pest PHP |
| Database | MySQL / PostgreSQL |
| Queue | Laravel Horizon (Redis) |
| Search | Laravel Scout (optional) |

---

## 🚀 Getting Started

### Requirements

- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 20.x & npm
- MySQL 8+ or PostgreSQL 15+
- Redis (for queues and caching)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-org/laravel-13-ngo-starter-kit.git
cd laravel-13-ngo-starter-kit

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file and configure
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ngo_db
# DB_USERNAME=root
# DB_PASSWORD=secret

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

Visit `http://localhost:8000` — default admin credentials are in `DatabaseSeeder.php`.

---

## 🗂 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/            # Admin panel controllers
│   │   ├── Donor/            # Donor-facing controllers
│   │   └── Volunteer/        # Volunteer portal controllers
│   ├── Models/               # Eloquent models
│   └── Services/             # Business logic layer
├── database/
│   ├── migrations/
│   └── seeders/              # Role, permission & demo data seeders
├── resources/
│   ├── js/                   # Alpine.js components
│   └── views/
│       ├── admin/            # Admin dashboard views
│       ├── donor/            # Donor portal views
│       └── volunteer/        # Volunteer portal views
├── routes/
│   ├── web.php
│   └── api.php
└── tests/
    ├── Feature/
    └── Unit/
```

---

## 👥 Roles & Permissions

| Role | Description |
|---|---|
| `super-admin` | Full system access |
| `admin` | Manage campaigns, donors, volunteers, reports |
| `staff` | Manage day-to-day operations and volunteers |
| `volunteer` | Access volunteer portal and log hours |
| `donor` | View donation history and campaign updates |

Roles and permissions are seeded automatically. You can customize them in `database/seeders/RolesAndPermissionsSeeder.php`.

---

## ⚙️ Configuration

Key environment variables to configure in your `.env`:

```env
# Application
APP_NAME="Your NGO Name"
APP_URL=https://yoursite.org

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_FROM_ADDRESS=hello@yoursite.org
MAIL_FROM_NAME="${APP_NAME}"

# Queue (recommended: Redis)
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1

# Media Storage
FILESYSTEM_DISK=public  # or s3 for production

# AWS S3 (optional)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

---

## 🧪 Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage report
php artisan test --coverage

# Run a specific test suite
php artisan test --testsuite=Feature
```

---

## 🚢 Deployment

### Production Checklist

```bash
# Optimize for production
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Build assets
npm ci && npm run build
```

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- Configure a process manager (Supervisor) for queue workers
- Set up scheduled tasks in your crontab:

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🤝 Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) before submitting a pull request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 💬 Support

- 📖 [Documentation](https://your-docs-site.com)
- 🐛 [Issue Tracker](https://github.com/your-org/laravel-13-ngo-starter-kit/issues)
- 💡 [Discussions](https://github.com/your-org/laravel-13-ngo-starter-kit/discussions)

---

> Built with ❤️ for organizations making a difference.
