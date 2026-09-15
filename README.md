# ServiceHub — Fully Working Laravel Portfolio Project

ServiceHub is a local-first **multi-vendor service booking and management system** built with Laravel 12, Blade, MySQL and Sanctum.

## Phase-by-phase implementation

### Phase 1 — Foundation & Authentication
- Laravel 12 project structure
- Customer / Provider / Admin roles
- Register, login, logout
- Role middleware
- Seeded demo accounts

### Phase 2 — Service Marketplace
- Categories
- Provider services
- Search and category filtering
- Service detail pages
- Ratings/review counters

### Phase 3 — Booking Workflow
- Customer creates booking
- Provider/customer relationships
- Date/time/address validation
- Booking status workflow
- Customer booking history
- Provider booking requests
- Admin booking management

### Phase 4 — Provider Panel
- Provider service CRUD
- Image upload to public storage
- Provider booking acceptance/completion
- Earnings dashboard

### Phase 5 — API / Mobile-ready backend
- Sanctum token authentication
- Register/login/logout API
- Service listing/detail API
- Customer booking list/create/detail API
- API validation and JSON responses

### Phase 6 — Payments & Reviews
- Demo payment flow that works without external credentials
- Payment records and references
- Booking confirmation after successful payment
- Customer reviews for completed bookings
- Automatic service rating recalculation

### Phase 7 — Admin & Portfolio readiness
- Category management
- Service management
- User role/status management
- Booking status management
- Responsive UI
- Seed data
- Feature tests
- README and API examples

## Setup on Windows + WAMP

### 1. Create database
Create a MySQL database named `servicehub` in phpMyAdmin.

### 2. Copy project
Recommended path:
`D:\wamp64\www\servicehub`

### 3. Install dependencies
Open PowerShell in the project folder:

```powershell
composer install
copy .env.example .env
php artisan key:generate
```

### 4. Configure `.env`
Use your WAMP MySQL settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=servicehub
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations + demo data
```powershell
php artisan migrate --seed
php artisan storage:link
```

### 6. Start Laravel
```powershell
php artisan serve
```
Open `http://127.0.0.1:8000` on the PC.

## Demo accounts
All passwords are `password`.

- Customer: `customer@servicehub.test`
- Provider: `provider@servicehub.test`
- Admin: `admin@servicehub.test`

## Show it on your phone
Connect the PC and phone to the same Wi-Fi.

```powershell
php artisan serve --host=0.0.0.0 --port=8000
ipconfig
```

Find the PC IPv4 address, for example `192.168.1.10`, then open on the phone:

`http://192.168.1.10:8000`

If Windows Firewall blocks it, allow TCP port 8000 for the current/private network.

## API examples

Base URL: `http://127.0.0.1:8000/api`

### Register
`POST /register`

```json
{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}
```

### Login
`POST /login`

```json
{"email":"customer@servicehub.test","password":"password"}
```

Use the returned Bearer token for protected endpoints.

### Services
`GET /services?q=cleaning`

### Create booking
`POST /bookings`

```json
{"service_id":1,"booking_date":"2026-09-20","booking_time":"14:00","address":"Vesu, Surat","customer_note":"Please call before arriving."}
```

## Payment note
The included payment feature is intentionally a **demo/local payment workflow** so the project works without Stripe/Razorpay credentials. It records a payment and confirms the booking. For a real production payment gateway, credentials, webhooks, signature verification, refund handling and provider payouts must be added and tested separately.

## Testing
Run:

```powershell
php artisan test
```

The project is designed as a strong portfolio/MVP application. Before production deployment, add production payment credentials/webhooks, mail provider, rate limiting, authorization policies, queues, backups, monitoring and security hardening.
