<?php

namespace App\Model;

use DateTime;

/**
 * Class CreditMemo
 * @package App\Model
 */
class CreditMemo
{
    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var int
     */
    protected $customerId;

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
    protected $creditMemoDate;

    /**
     * @var int
     */
    protected $creditMemoId;

    /**
     * @var string
     */
    protected $creditMemoIncrementId;

    /**
     * @var string
     */
    protected $refundMethod;

    /**
     * @var string
     */
    protected $refundTransactionId;

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
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     * @return CreditMemo
     */
    public function setCustomerId(int $customerId): CreditMemo
    {
        $this->customerId = $customerId;
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
     * @return CreditMemo
     */
    public function setOrderId(int $orderId): CreditMemo
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
     * @return CreditMemo
     */
    public function setOrderIncrementId(string $orderIncrementId): CreditMemo
    {
        $this->orderIncrementId = $orderIncrementId;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreditMemoDate(): DateTime
    {
        return $this->creditMemoDate;
    }

    /**
     * @param DateTime $creditMemoDate
     * @return CreditMemo
     */
    public function setCreditMemoDate(DateTime $creditMemoDate): CreditMemo
    {
        $this->creditMemoDate = $creditMemoDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreditMemoId(): int
    {
        return $this->creditMemoId;
    }

    /**
     * @param int $creditMemoId
     * @return CreditMemo
     */
    public function setCreditMemoId(int $creditMemoId): CreditMemo
    {
        $this->creditMemoId = $creditMemoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreditMemoIncrementId(): string
    {
        return $this->creditMemoIncrementId;
    }

    /**
     * @param string $creditMemoIncrementId
     * @return CreditMemo
     */
    public function setCreditMemoIncrementId(string $creditMemoIncrementId): CreditMemo
    {
        $this->creditMemoIncrementId = $creditMemoIncrementId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefundMethod(): string
    {
        return $this->refundMethod;
    }

    /**
     * @param string $refundMethod
     * @return CreditMemo
     */
    public function setRefundMethod(string $refundMethod): CreditMemo
    {
        $this->refundMethod = $refundMethod;
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
     * @return CreditMemo
     */
    public function setAccountId(string $accountId): CreditMemo
    {
        $this->accountId = $accountId;
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
     * @return CreditMemo
     */
    public function setStatus(string $status): CreditMemo
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
     * @return CreditMemo
     */
    public function setPdfUrl(string $pdfUrl): CreditMemo
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
     * @return CreditMemo
     */
    public function setLineItems(array $lineItems): CreditMemo
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
     * @return CreditMemo
     */
    public function setCurrency(string $currency): CreditMemo
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
