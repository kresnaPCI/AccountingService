<?php

namespace App\Command\CreditMemo;

use DateTime;

/**
 * Class UpdateDateCommand
 * @package App\Command\CreditMemo
 */
class UpdateDateCommand
{
    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var int
     */
    protected $creditMemoId;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * UpdateDateCommand constructor.
     * @param string $accountId
     * @param int $creditMemoId
     * @param DateTime $date
     * @param string $pdfUrl
     */
    public function __construct(string $accountId, int $creditMemoId, DateTime $date, string $pdfUrl)
    {
        $this->accountId = $accountId;
        $this->creditMemoId = $creditMemoId;
        $this->date = $date;
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
    public function getCreditMemoId(): int
    {
        return $this->creditMemoId;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getPdfUrl(): string
    {
        return $this->pdfUrl;
    }
}
