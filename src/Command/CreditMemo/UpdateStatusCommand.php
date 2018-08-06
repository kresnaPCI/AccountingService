<?php

namespace App\Command\CreditMemo;

/**
 * Class UpdateStatusCommand
 * @package App\Command\CreditMemo
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
     * @param string $accountId
     * @param int $creditMemoId
     * @param string $status
     * @param string $pdfUrl
     */
    public function __construct(string $accountId, int $creditMemoId, string $status, string $pdfUrl)
    {
        $this->accountId = $accountId;
        $this->creditMemoId = $creditMemoId;
        $this->status = $status;
        $this->pdfUrl = $pdfUrl;
    }

    /**
     * @return int
     */
    public function getCreditMemoId(): int
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
}
