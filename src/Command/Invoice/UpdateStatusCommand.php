<?php

declare(strict_types=1);

namespace App\Command\Invoice;

/**
 * Class UpdateStatusCommand
 * @package App\Command\Invoice
 */
class UpdateStatusCommand
{
    /**
     * @var int
     */
    protected $invoiceId;

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * UpdateStatusCommand constructor.
     * @param string $accountId
     * @param int $invoiceId
     * @param string $status
     * @param string $pdfUrl
     */
    public function __construct(string $accountId, int $invoiceId, string $status, string $pdfUrl)
    {
        $this->accountId = $accountId;
        $this->invoiceId = $invoiceId;
        $this->status = $status;
        $this->pdfUrl = $pdfUrl;
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
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getPdfUrl(): string
    {
        return $this->pdfUrl;
    }
}
