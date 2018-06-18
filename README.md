# Accounting Service


## API

The API will be described here.

### Invoice

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

