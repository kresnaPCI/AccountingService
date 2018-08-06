<?php

namespace App\Command\CreditMemo;

/**
 * Class RefundCommand
 * @package App\Command\CreditMemo
 */
class RefundCommand
{
    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var int
     */
    protected $invoiceId;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * RefundCommand constructor.
     * @param string $accountId
     * @param int $invoiceId
     * @param string $method
     * @param string $transactionId
     * @param string $pdfUrl
     */
    public function __construct(
        string $accountId,
        int $invoiceId,
        string $method,
        string $transactionId,
        string $pdfUrl
    ) {
        $this->accountId = $accountId;
        $this->invoiceId = $invoiceId;
        $this->transactionId = $transactionId;
        $this->pdfUrl = $pdfUrl;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return int
     */
    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getPdfUrl(): string
    {
        return $this->pdfUrl;
    }
}
