<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15/6/2018 AD
 * Time: 14:12
 */

namespace App\Command\Invoice;

use DateTime;

/**
 * Class UpdateDateCommand
 * @package App\Command\Invoice
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
    protected $invoiceId;

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
     * @param int $invoiceId
     * @param DateTime $date
     * @param string $pdfUrl
     */
    public function __construct(string $accountId, int $invoiceId, DateTime $date, string $pdfUrl)
    {
        $this->accountId = $accountId;
        $this->invoiceId = $invoiceId;
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
    public function getInvoiceId(): int
    {
        return $this->invoiceId;
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
