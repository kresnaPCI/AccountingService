<?php

namespace App\Model;

use DateTime;
/**
 * Class Invoice
 * @package App\Model
 */
class Invoice
{
    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $customerName;

    /**
     * @var string
     */
    protected $customerEmail;

    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $orderIncrementId;

    /**
     * @var DateTime
     */
    protected $orderDate;

    /**
     * @var int
     */
    protected $invoiceId;

    /**
     * @var string
     */
    protected $invoiceIncrementId;

    /**
     * @var DateTime
     */
    protected $invoiceDate;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var string|null
     */
    protected $paymentTransactionId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * @var LineItem[]
     */
    protected $lineItems;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     * @return Invoice
     */
    public function setCustomerId(string $customerId): Invoice
    {
        $this->customerId = $customerId;
        return $this;
    }
    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     * @return Invoice
     */
    public function setCustomerName(string $customerName): Invoice
    {
        $this->customerName = $customerName;
        return $this;
    }

     /**
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     * @return Invoice
     */
    public function setCustomerEmail(string $customerEmail): Invoice
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }
    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     * @return Invoice
     */
    public function setOrderId(int $orderId): Invoice
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderIncrementId(): string
    {
        return $this->orderIncrementId;
    }

    /**
     * @param string $orderIncrementId
     * @return Invoice
     */
    public function setOrderIncrementId(string $orderIncrementId): Invoice
    {
        $this->orderIncrementId = $orderIncrementId;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    /**
     * @param DateTime $orderDate
     * @return Invoice
     */
    public function setOrderDate(DateTime $orderDate): Invoice
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    /**
     * @param int $invoiceId
     * @return Invoice
     */
    public function setInvoiceId(int $invoiceId): Invoice
    {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceIncrementId(): string
    {
        return $this->invoiceIncrementId;
    }

    /**
     * @param string $invoiceIncrementId
     * @return Invoice
     */
    public function setInvoiceIncrementId(string $invoiceIncrementId): Invoice
    {
        $this->invoiceIncrementId = $invoiceIncrementId;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInvoiceDate(): DateTime
    {
        return $this->invoiceDate;
    }

    /**
     * @param DateTime $invoiceDate
     * @return Invoice
     */
    public function setInvoiceDate(DateTime $invoiceDate): Invoice
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return Invoice
     */
    public function setAccountId(string $accountId): Invoice
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     * @return Invoice
     */
    public function setPaymentMethod(string $paymentMethod): Invoice
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentTransactionId(): ?string
    {
        return $this->paymentTransactionId;
    }

    /**
     * @param string $paymentTransactionId
     * @return Invoice
     */
    public function setPaymentTransactionId(string $paymentTransactionId): Invoice
    {
        $this->paymentTransactionId = $paymentTransactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Invoice
     */
    public function setStatus(string $status): Invoice
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPdfUrl(): string
    {
        return $this->pdfUrl;
    }

    /**
     * @param string $pdfUrl
     * @return Invoice
     */
    public function setPdfUrl(string $pdfUrl): Invoice
    {
        $this->pdfUrl = $pdfUrl;
        return $this;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    /**
     * @param LineItem[] $lineItems
     * @return Invoice
     */
    public function setLineItems(array $lineItems): Invoice
    {
        $this->lineItems = $lineItems;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Invoice
     */
    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxAmount(): float
    {
        $tax = 0;

        foreach ($this->lineItems as $lineItem) {
            $tax += $lineItem->getTaxAmount();
        }

        return $tax;
    }

    /**
     * @return float
     */
    public function getTotalExcTax(): float
    {
        $total = 0;

        foreach ($this->lineItems as $lineItem) {
            $total += $lineItem->getTotalExcTax();
        }

        return $total;
    }

    /**
     * @return float
     */
    public function getTotalIncTax(): float
    {
        $total = 0;

        foreach ($this->lineItems as $lineItem) {
            $total += $lineItem->getTotalIncTax();
        }

        return $total;
    }
}
