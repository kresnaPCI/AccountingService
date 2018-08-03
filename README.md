# Accounting Service

## Installation

To get the code up and running:

```
# Setup Env Config
$ cp .env.dist .env  # Configure .env if needed

# Run Composer
$ composer install

# Run Server
$ php bin/console server:run

```

## API

The Rest API mimics the same functionality and data structure that would be used through the message queue.

This allows easier testing in development without needing to setup message queues and send messages.

### Invoice

Invoices can be created and updated through the API.

#### Create

Request

`POST /account/<accountId>/invoice`

Body

```
{
	"customerId": 1234,
	"currency": "thb",
	"invoiceId": 1234,
	"invoiceIncrementId": "AB/12345",
	"invoiceDate": "2018-06-01T12:34:56+07:00",
	"orderId": 1234,
	"orderIncrementId": "CD/12345",
	"orderDate": "2018-06-01T12:34:56+07:00",
	"paymentMethod": "omise",
	"paymentTransactionId": "chrg_123234",
	"pdfUrl": "http://invoices/invoice.pdf",
	"status": "paid",
	"lineItems": [
		{"sku": "abc123", "unitPrice": 1.25, "quantity": 123, "taxRate": 7, "taxIdentifier": "VAT", "discount": 0},
		{"sku": "abc124", "unitPrice": 1.5, "quantity": 100, "taxRate": 7, "taxIdentifier": "VAT", "discount": 10}
	]
}
```

#### Update Date

Request

`POST /account/<accountId>/invoice/<invoiceId>/date`

Body

```
{
	"date": "2018-06-01T12:34:56+07:00",
	"pdfUrl": "http://invoices/invoice.pdf"
}
```

#### Pay

Request

`POST /account/<accountId>/invoice/<invoiceId>/pay`

Body

```
{
	"transactionId": "123456780",
	"pdfUrl": "http://invoices/invoice.pdf"
}
```

#### Update Status

Valid Status:
- `cancelled`
- `pending`

Request

`POST /account/<accountId>/invoice/<invoiceId>/status`

Body

```
{
	"status": "pending",
	"pdfUrl": "http://invoices/invoice.pdf"
}
```


### Credit Memo

Credit Memos can be created and updated through the API.

#### Create

Request

`POST /account/<accountId>/creditmemo`

Body

```
{
	"customerId": 1234,
	"currency": "thb",
	"creditMemoId": 1234,
	"creditMemoIncrementId": "AB/12345",
	"creditMemoDate": "2018-06-01T12:34:56+07:00",
	"orderId": 1234,
	"orderIncrementId": "CD/12345",
	"refundMethod": "omise",
	"refundTransactionId": "rfnd_123234",
	"pdfUrl": "http://invoices/invoice.pdf",
	"status": "refunded",
	"lineItems": [
		{"sku": "abc123", "unitPrice": 1.25, "quantity": 123, "taxRate": 7, "taxIdentifier": "VAT", "discount": 0},
		{"sku": "abc124", "unitPrice": 1.5, "quantity": 100, "taxRate": 7, "taxIdentifier": "VAT", "discount": 10}
	]
}
```

#### Refund

Request

`POST /account/<accountId>/creditmemo/<creditMemoId>/refund`

Body

```
{
	"method": "omise",
	"transactionId": "rfnd_123234",
	"pdfUrl": "http://invoices/invoice.pdf"
}
```