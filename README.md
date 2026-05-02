# CharityHub - Nonprofit Donation & Campaign Management Platform
<img width="595" height="751" alt="image" src="https://github.com/user-attachments/assets/25da90a4-6f59-44aa-af13-b6dbaecbb208" />


## 🚀 Overview
CharityHub is a transparent online platform designed to manage fundraising campaigns, accept donations, coordinate volunteers, and publish impact reports to build donor trust.

---

## ✅ What Has Been Completed (My Work)
The core foundation and frontend UI have been successfully implemented:

1. **Frontend UI (Premium Theme):**
   - Designed a highly professional **Frosted Lilac Glassmorphism** theme using Custom CSS.
   - Fully responsive layouts using Bootstrap 5 for grids.
   - Dynamic, animated backgrounds and soft hover effects.
2. **Volunteer System:**
   - Volunteer registration and task tracking.
   - Implemented a custom **Conflict Detection Algorithm** to prevent volunteers from registering for tasks overlapping in time.
3. **Campaign Management (CRUD):**
   - Full backend controllers to Create, Read, Update, and Delete campaigns.
   - Dynamic progress bars calculating raised amounts vs. goals.
   - SEO-friendly URL generation (Slugs).
4. **Donations Engine Core:**
   - Built the database structures and relations for donations.
   - Implemented **Idempotency Keys** to securely prevent duplicate donation charges.
5. **Impact Reports:**
   - Created a dynamic impact report dashboard aggregating total donations, active campaigns, and volunteer hours.
6. **Authentication & Roles (RBAC):**
   - Implemented Spatie Laravel-permission.
   - Secured routes using `@can` and Middleware for (Admin, Manager, Donor, Volunteer).

## 🛠️ Technologies Used So Far
- **Backend:** Laravel 11 (Manual coding & architecture)
- **Database:** MySQL (using XAMPP)
- **Frontend:** Blade Templating, Bootstrap 5 (Structure), Custom CSS (Aesthetics)
- **Fonts:** Google Fonts (Outfit)

---

## ⏳ Pending Tasks (For Next Team Members)
Based on the Project Criteria, the following features are left to be implemented by the rest of the team:

### 1. Payment Integration (Backend Team)
- Integrate **Stripe Billing** or **PayMob** for live payment processing.
- Handle both one-time and recurring (subscription) donations.
- Implement an abstract Payment Interface for easy gateway swapping.

### 2. PDF & Email Jobs (Backend Team)
- Install and configure **DomPDF**.
- Generate automated donor certificates containing a **QR Code**.
- Setup Laravel Queued Jobs and Events (`DonationReceived` event) to send the certificate via email asynchronously.

### 3. Real-Time UI Updates (Frontend Team)
- Integrate **Livewire** to make the campaign progress bars and donation feeds update in real-time without refreshing the page.

### 4. Maps Integration (Frontend Team)
- Integrate **Google Maps API** into the Impact Reports page to show beneficiary locations.

### 5. Final Security Features
- Implement the manual "Forget Password" flow with token generation (Chapter 6 requirement).

---

## 💻 How to Run Locally
1. Clone the repository.
2. Run `composer install`
3. Copy `.env.example` to `.env` (ensure `DB_CONNECTION=mysql` and your database exists in phpMyAdmin).
4. Run `php artisan key:generate`
5. Run `php artisan migrate:fresh --seed`
6. Run `php artisan serve`

### 🔑 Default Admin Account
To test the admin features, use the following credentials:
- **Email:** `admin@charityhub.local`
- **Password:** `password123`
