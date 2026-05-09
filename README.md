<div align="center">
  <h1>🌍 CharityHub</h1>
  <p>A modern, feature-rich, and secure non-profit donation and volunteer management platform built with Laravel 11.</p>
</div>

---

## 📖 Overview

CharityHub is a comprehensive web application designed to empower non-profit organizations to effortlessly manage fundraising campaigns, process secure donations, organize volunteer activities, and issue automated donor certificates. 

The platform boasts a dedicated volunteer scheduling interface and a robust administrative backend panel for complete oversight.

## ✨ Key Features

### 💰 Donations & Payments (Stripe)
- **Flexible Contributions:** Support for both **One-time** and **Recurring (Monthly)** donations.
- **Secure Processing:** Seamlessly integrated with **Stripe Checkout**.
- **Automated Webhooks:** Listens to Stripe events for real-time synchronization of recurring subscription payments.
- **Idempotency Protection:** Prevents accidental double-charging if a user refreshes the payment page.

### 📜 Automated Donor Certificates
- **Dynamic PDF Generation:** Automatically generates beautifully formatted PDF certificates using `DOMPDF` upon successful donations.
- **Automated Email Delivery:** Instantly emails the certificate to the donor using Laravel Queues.
- **Secure Access (IDOR Prevention):** Strict authorization ensures donors can only download their own certificates.
- **Public Verification:** Unique URLs allow third-party verification of certificate authenticity.

### 🗺️ Campaigns & Geolocation
- **Campaign Management:** Full CRUD capabilities with secure file uploads for campaign banner images.
- **SEO-Friendly Slugs:** Dynamic, human-readable URLs for campaign pages.
- **Google Maps Integration:** Dynamically renders a Google Map showing the exact physical location of a campaign based on its latitude and longitude.

### 🤝 Volunteer Management
- **Interactive Scheduling:** Features an interactive **FullCalendar.js** interface for volunteers to browse upcoming tasks.
- **Smart Registration:** Enforces task capacities, deadline checks, and prevents schedule conflicts (double-booking on the same day).
- **Secure Hour Logging:** Dedicated UI allowing registered volunteers to accurately log their fractional hours worked, with strict backend validation preventing them from logging more hours than the task requires.

### 🔐 Authentication & Authorization
- **Google OAuth:** One-click registration and login via Google.
- **Role-Based Access Control:** Powered by Spatie Laravel-Permission (Admin vs. Standard User).
- **Secure Admin Panel:** An isolated, elegant administrative dashboard powered by **Filament PHP** restricted exclusively to users with the 'Admin' role.

## 🛠️ Technology Stack

- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL
- **Admin Panel:** Filament PHP v3
- **Payment Gateway:** Stripe PHP SDK
- **OAuth:** Laravel Socialite (Google)
- **PDF Generation:** Barryvdh/Laravel-DomPDF
- **Frontend Assets:** Bootstrap 5, FullCalendar.js, Google Maps JS API
- **Testing:** PHPUnit (Feature Testing with Mockery)

## 📋 Requirements

- PHP >= 8.2 (with `intl` and `gd` extensions enabled)
- Composer
- MySQL >= 8.0
- Node.js & NPM (for Vite assets)
- Stripe Account (for API keys)
- Google Cloud Console Account (for OAuth and Maps APIs)

## 🚀 Installation & Setup

**1. Clone the repository**
```bash
git clone https://github.com/your-username/charityhub.git
cd charityhub
```

**2. Install PHP Dependencies**
```bash
composer install
```

**3. Configure the Environment**
Copy the example environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```
Open the `.env` file and configure your database credentials, Stripe keys, Mailtrap/SMTP settings, and Google APIs. *(See the documented `.env.example` for detailed instructions).*

**4. Link Storage**
Create the symbolic link to allow public access to uploaded campaign images:
```bash
php artisan storage:link
```

**5. Run Migrations & Seeders**
Migrate the database and seed it with the default roles (Admin/User):
```bash
php artisan migrate --seed
```

**6. Start the Application**
Start the background queue worker (for emails) and the local development server:
```bash
php artisan queue:work
php artisan serve
```

## 🧪 Testing
CharityHub features a robust, automated feature testing suite that uses Mockery to safely intercept third-party API calls. 

To run the test suite:
```bash
php artisan test
```

## 🛡️ Security Implementation
- **Mass Assignment:** All Eloquent models are strictly guarded.
- **Broken Access Control (IDOR):** Verified via PHPUnit that users cannot access or modify resources belonging to others (e.g., Certificates, Volunteer Hours).
- **File Upload Security:** Enforces MIME type validation and strictly stores images on the isolated `public` disk.
- **Route Isolation:** The `/admin` Filament panel is strictly segregated from the standard `web` middleware group to prevent route collision and unauthorized access.
