# Online Payments by JPM - Laravel Implementation

This is a Laravel-based implementation of the Online Payments API (v2.7.0) from JPM. It's based on the OpenAPI Specification provided on their developer portal (https://developer.payments.jpmorgan.com/api/commerce/online-payments/online-payments/changelog#april-24-2025).

This API mimicks all functionality of the JPM Payments API and comes with the Treblle SDK integrated.

## Requirements

- PHP 8.2 or higher
- Composer
- SQLite (included with PHP)

## Installation

### Option 1: Docker (Recommended - Easiest Setup)

This project includes Laravel Sail for Docker-based development. You can use Docker without installing PHP or Composer on your machine:

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd online-payments-api
   ```

2. **Install Composer dependencies**
   
   **Option A: Using Composer on your machine** (requires Composer installed)
   ```bash
   composer install --ignore-platform-reqs
   ```
   
   *To install Composer: Download from [getcomposer.org](https://getcomposer.org/download/) or use `brew install composer` on macOS*
   
   **Option B: Using Docker without Composer** (no Composer installation needed)
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   ```
   Configure the Treblle SDK Token and API Key in the `.env` file

4. **Start with Laravel Sail**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generate application key**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

The API will be available at `http://localhost:8080/api/v2/`


### Option 2: Local PHP Installation

#### Windows

1. **Install PHP**
   - Download PHP 8.2+ from [windows.php.net](https://windows.php.net/download/)
   - Or use [XAMPP](https://www.apachefriends.org/) which includes PHP, Apache, and MySQL
   - Or use [Laragon](https://laragon.org/) for a modern Windows development environment

2. **Install Composer**
   - Download from [getcomposer.org](https://getcomposer.org/download/)
   - Run the Windows installer

3. **Setup the project**
   ```bash
   git clone <repository-url>
   cd online-payments-api
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

#### macOS

1. **Install PHP using Homebrew**
   ```bash
   # Install Homebrew if not already installed
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   
   # Install PHP 8.2+
   brew install php@8.2
   brew link php@8.2 --force
   ```

2. **Install Composer**
   ```bash
   # Using Homebrew
   brew install composer
   
   # Or download directly
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. **Setup the project**
   ```bash
   git clone <repository-url>
   cd online-payments-api
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

#### Linux (Ubuntu/Debian)

```bash
# Install PHP and required extensions
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-curl php8.2-zip php8.2-gd php8.2-mbstring php8.2-xml php8.2-sqlite3

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Setup project
git clone <repository-url>
cd online-payments-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Getting Started

1. **Configure your environment**
   Edit the `.env` file and configure the Treblle SDK Token and API Key

2. **Start the Development Server**
   
   **Using Docker:**
   ```bash
   ./vendor/bin/sail up -d
   ```
   
   **Using local PHP:**
   ```bash
   php artisan serve
   ```

3. **Test the API**
   The API will be available at:
   - Docker: `http://localhost:8080/api/v2/`
   - Local PHP: `http://localhost:8000/api/v2/`

   ```bash
   # For Docker
   curl http://localhost:8080/api/v2/healthcheck/payments
   
   # For local PHP
   curl http://localhost:8000/api/v2/healthcheck/payments
   ```


## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HealthCheckController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── CaptureController.php
│   │   │   ├── RefundController.php
│   │   │   ├── VerificationController.php
│   │   │   └── FraudCheckController.php
│   │   ├── Middleware/
│   │   │   └── ValidateApiHeaders.php
│   │   └── Requests/
│   │       ├── CreatePaymentRequest.php
│   │       ├── CreateCaptureRequest.php
│   │       ├── CreateRefundRequest.php
│   │       └── CreateVerificationRequest.php
│   ├── Services/
│   │   └── MockDataService.php
│   └── Traits/
│       └── ApiResponse.php
├── routes/
│   └── api.php
└── bootstrap/
    └── app.php (configured with API middleware and exception handling)
```

## API Endpoints

### Health Check Endpoints
- `GET /api/v2/healthcheck/payments` - Health check for payments service
- `GET /api/v2/healthcheck/refunds` - Health check for refunds service  
- `GET /api/v2/healthcheck/verifications` - Health check for verifications service

### Payment Endpoints
- `POST /api/v2/payments` - Create a payment request
- `GET /api/v2/payments` - Get payment transactions (with pagination)
- `GET /api/v2/payments/{id}` - Get specific payment details
- `PATCH /api/v2/payments/{id}` - Update payment transaction

### Capture Endpoints
- `POST /api/v2/payments/{id}/captures` - Capture an authorized payment
- `GET /api/v2/captures` - Get capture transactions (with pagination)
- `GET /api/v2/captures/{id}` - Get specific capture details

### Refund Endpoints
- `POST /api/v2/refunds` - Create a refund (standalone or referenced)
- `GET /api/v2/refunds` - Get refund transactions (with pagination)
- `GET /api/v2/refunds/{id}` - Get specific refund details

### Verification Endpoints
- `POST /api/v2/verifications` - Create payment method verification
- `GET /api/v2/verifications` - Get verification transactions (with pagination)
- `GET /api/v2/verifications/{id}` - Get specific verification details

### Fraud Check Endpoints
- `POST /api/v2/fraudcheck` - Create fraud check request
- `GET /api/v2/fraudcheck` - Get fraud check transactions (with pagination)
- `GET /api/v2/fraudcheck/{id}` - Get specific fraud check details

## Required Headers
All API endpoints (except health checks) require the following headers:

- `merchant-id`: Merchant account identifier (8-12 characters)
- `request-id`: Unique request identifier (max 40 characters)
- `Content-Type`: application/json
- `Accept`: application/json

Optional headers:
- `minorVersion`: API minor version identifier

## Sample Requests

### Create a Payment

```bash
curl -X POST "http://localhost:8000/api/v2/payments" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "merchant-id: 998482157630" \
  -H "request-id: 10cc0270-7bed-11e9-a188-1763956dd7f6" \
  -d '{
    "amount": 1234,
    "currency": "USD",
    "captureMethod": "LATER",
    "merchant": {
      "merchantCategoryCode": "4899"
    },
    "paymentMethodType": {
      "card": {
        "accountNumber": "4012000033330026",
        "expiry": {
          "month": "12",
          "year": "2025"
        }
      }
    }
  }'
```

### Capture a Payment

```bash
curl -X POST "http://localhost:8000/api/v2/payments/txn_12345/captures" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "merchant-id: 998482157630" \
  -H "request-id: 11cc0270-7bed-11e9-a188-1763956dd7f6" \
  -d '{
    "amount": 1234,
    "currency": "USD",
    "finalCapture": true
  }'
```

### Create a Refund

```bash
curl -X POST "http://localhost:8000/api/v2/refunds" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "merchant-id: 998482157630" \
  -H "request-id: 12cc0270-7bed-11e9-a188-1763956dd7f6" \
  -d '{
    "amount": 500,
    "currency": "USD",
    "parentTransactionId": "txn_12345",
    "merchant": {
      "merchantCategoryCode": "4899"
    }
  }'
```

## Response Format

### Success Response Example
```json
{
  "transactionId": "txn_12345",
  "requestId": "10cc0270-7bed-11e9-a188-1763956dd7f6",
  "transactionState": "AUTHORIZED",
  "responseStatus": "APPROVED",
  "responseCode": "000",
  "responseMessage": "Approved",
  "amount": 1234,
  "currency": "USD",
  "merchantId": "998482157630",
  "createdAt": "2025-07-01T12:00:00.000Z"
}
```

### Error Response Example
```json
{
  "responseStatus": "ERROR",
  "responseCode": "400",
  "responseMessage": "Missing required header: merchant-id"
}
```

### Validation Error Response Example
```json
{
  "responseStatus": "ERROR",
  "responseCode": "400",
  "responseMessage": "Validation failed",
  "errors": {
    "amount": ["Payment amount is required"],
    "currency": ["Currency code is required"]
  }
}
```