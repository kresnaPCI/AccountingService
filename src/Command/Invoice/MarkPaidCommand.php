<?php

namespace App\Command\Invoice;

/**
 * Class MarkPaidCommand
 * @package App\Command\Invoice
 */
class MarkPaidCommand
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
    protected $transactionId;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * MarkPaidCommand constructor.
     * @param string $accountId
     * @param int $invoiceId
     * @param string $transactionId
     * @param string $pdfUrl
     */
    public function __construct(string $accountId, int $invoiceId, string $transactionId, string $pdfUrl)
    {
        $this->accountId = $accountId;
        $this->invoiceId = $invoiceId;
        $this->transactionId = $transactionId;
        $this->pdfUrl = $pdfUrl;
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
