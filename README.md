# Online Payments by JPM - Laravel Implementation

This is a Laravel-based implementation of the Online Payments API (v2.7.0) from JPM. It's based on the OpenAPI Specification provided on their developer portal (https://developer.payments.jpmorgan.com/api/commerce/online-payments/online-payments/changelog#april-24-2025).

This API mimicks all functionality of the JPM Payments API and comes with the Treblle SDK integrated.

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

## Sample Usage

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

## Key Components

### MockDataService
Generates realistic mock responses for all transaction types with proper:
- Transaction IDs and request tracking
- Payment method masking for security
- Processor response simulation
- Fraud rule evaluation
- Risk scoring algorithms

### Validation
- Comprehensive input validation using Laravel Form Requests
- Required header validation via middleware
- Error response standardization
- Currency code and amount validation

### Security Features
- Card number masking in all responses
- Required merchant authentication headers
- Request ID tracking for idempotency
- Proper error messages without sensitive data exposure

## Getting Started

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Start the Development Server**
   ```bash
   php artisan serve
   ```

3. **Test the API**
   The API will be available at `http://localhost:8000/api/v2/`

4. **Health Check**
   ```bash
   curl http://localhost:8000/api/v2/healthcheck/payments
   ```

## Configuration

The API is configured to use SQLite database (though not actively used for mock responses). All configuration is standard Laravel setup with custom middleware and exception handling for API-specific requirements.

## Mock Data Behavior

- All payment requests return APPROVED status with realistic transaction IDs
- Card numbers are automatically masked (showing only last 4 digits)
- Fraud checks include randomized risk scores and rule evaluations
- All timestamps use ISO 8601 format
- Pagination defaults to 10 items per page
- Transaction states follow the OpenAPI specification

This implementation provides a fully functional API that matches the OpenAPI 2.7.0 specification behavior while using mock data instead of processing real transactions.