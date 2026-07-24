# ERP External Module Integration

## Architecture

```
┌──────────────────────────────┐      ┌─────────────────────────────┐
│   Simulator UI (Admin)       │      │   Web Services (API)        │
│   routes/admin/external/*    │      │   /api/external/*           │
│   Auth: session + role       │      │   Auth: Bearer token        │
│   Purpose: trigger + view    │      │   Purpose: external ERP     │
└──────────┬───────────────────┘      └───────────┬─────────────────┘
           │                                      │
           │  calls with Bearer token             │
           └──────────────────────────────────────┘
                            │
                            ▼
              ┌──────────────────────────┐
              │   WebhookService         │
              │   → webhook_logs table   │
              │   → DB updates           │
              └──────────────────────────┘
```

## Routes

### Web Service API (`routes/api.php`)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/external/ping` | None | Health check |
| POST | `/api/external/finance/payment-confirmed` | Finance Bearer token | Confirm payment |
| GET | `/api/external/finance/orders` | Finance Bearer token | List unpaid orders |
| GET | `/api/external/sales/order/{order_number}` | Sales Bearer token | Get order details |
| GET | `/api/external/sales/orders` | Sales Bearer token | List paid orders |
| POST | `/api/external/sales/update-status` | Sales Bearer token | Update fulfillment status |

### Simulator UI (`routes/external.php`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/external/simulator` | Simulator page |
| GET | `/admin/external/simulator/list` | JSON list data for polling |
| GET | `/admin/external/simulator/logs` | Webhook log entries |
| GET | `/admin/external/simulator/order/{order_number}` | Order detail lookup |

## Data Ownership

| Module | Owns | Can modify |
|--------|------|-----------|
| **Finance & Accounting** | `payment_status` | `payment_status`, `finance_transaction_id` |
| **Sales Management** | `order_status` | `status`, tracking |

## Real Data Exchange Flow

### 1. Finance Confirms Payment

```
Ecommerce → Finance:   Order created (via WebhookService)
Finance → Ecommerce:   POST /api/external/finance/payment-confirmed
                       { order_number, finance_transaction_id, paid_at }
                       Header: Authorization: Bearer {FINANCE_API_KEY}
```

FinanceController updates:
- `payment_status` → "paid"
- `status` → "processing"
- `finance_transaction_id` → stored
- Fires `WebhookService::paymentConfirmed()` → logged in `webhook_logs`

### 2. Sales Updates Status

```
Ecommerce → Sales:     Order + payment confirmed (via WebhookService)
Sales → Ecommerce:     POST /api/external/sales/update-status
                       { order_number, status }
                       Header: Authorization: Bearer {SALES_API_KEY}
```

SalesController validates:
- `payment_status` must be "paid" (rejects if not)
- Updates `status` + tracking
- Returns updated order

## Key Files

| File | Role |
|------|------|
| `routes/api.php` | API endpoint definitions |
| `routes/external.php` | Simulator UI routes |
| `app/Http/Controllers/Api/External/FinanceController.php` | Finance web service logic |
| `app/Http/Controllers/Api/External/SalesController.php` | Sales web service logic |
| `app/Http/Controllers/Admin/ExternalSimulatorController.php` | Simulator UI controller |
| `app/Http/Middleware/ExternalApiAuth.php` | Bearer token validation |
| `app/Services/External/WebhookService.php` | Webhook dispatch + logging |
| `app/Models/WebhookLog.php` | Audit trail model |
| `config/external-modules.php` | API keys + webhook URLs per module |
| `resources/views/pages/admin/external-simulator/index.blade.php` | Simulator UI |

## API Key Setup (.env)

```
FINANCE_API_KEY=sk-finance-dev-abc123def456
FINANCE_WEBHOOK_URL=https://finance-system.test/api/orders
SALES_API_KEY=sk-sales-dev-789ghi012jkl
SALES_WEBHOOK_URL=https://sales-system.test/api/orders
```

## Testing via curl (for presentation)

```bash
# Finance lists unpaid orders
curl http://localhost/api/external/finance/orders \
  -H "Authorization: Bearer sk-finance-dev-abc123def456"

# Finance confirms payment
curl -X POST http://localhost/api/external/finance/payment-confirmed \
  -H "Authorization: Bearer sk-finance-dev-abc123def456" \
  -H "Content-Type: application/json" \
  -d '{"order_number":"OID-1234-5678","finance_transaction_id":"FA-TXN-001","paid_at":"2026-07-24T12:00:00Z"}'

# Sales lists paid orders
curl http://localhost/api/external/sales/orders \
  -H "Authorization: Bearer sk-sales-dev-789ghi012jkl"

# Sales fetches order details
curl http://localhost/api/external/sales/order/OID-1234-5678 \
  -H "Authorization: Bearer sk-sales-dev-789ghi012jkl"

# Sales updates status
curl -X POST http://localhost/api/external/sales/update-status \
  -H "Authorization: Bearer sk-sales-dev-789ghi012jkl" \
  -H "Content-Type: application/json" \
  -d '{"order_number":"OID-1234-5678","status":"shipped"}'
```
