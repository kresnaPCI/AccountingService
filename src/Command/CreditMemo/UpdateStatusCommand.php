<?php

declare(strict_types=1);



namespace App\Command\CreditMemo;

/**
 * Class UpdateStatusCommand
 * @package App\Command\Invoice
 */
class UpdateStatusCommand
{
    /**
     * @var int
     */
    protected $creditMemoId;

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
     * @param int $invoiceId
     * @param string $accountId accountId
     * @param string $status
     * @param string $pdfUrl
     */
    public function __construct(int $creditMemoId, string $accountId, string $transactionId, string $pdfUrl, string $status, string $paymentType, string $partnerType)
    {
        $this->creditMemoId = $creditMemoId;
        $this->status = $status;
        $this->pdfUrl = $pdfUrl;
        $this->accountId = $accountId;
        $this->transactionId = $transactionId;
        $this->paymentType = $paymentType;
        $this->partnerType = $partnerType;
    }

    /**
     * @return int
     */
    public function getcreditMemoId(): int
    {
        return $this->creditMemoId;
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
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @return string
     */
    public function getPartnerType(): string
    {
        return $this->partnerType;
    }
}
