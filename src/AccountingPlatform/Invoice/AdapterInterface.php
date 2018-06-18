<?php

declare(strict_types=1);

namespace App\AccountingPlatform\Invoice;

use App\Model\Invoice;
use DateTime;

/**
 * Interface AdapterInterface
 * @package App\AccountingPlatform\Invoice
 */
interface AdapterInterface
{
    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function create(Invoice $invoice): bool;

    /**
     * @param string $accountId
     * @param int $invoiceId
     * @param DateTime $date
     * @param string $pdfLink
     * @return bool
     */
    public function updateDate(string $accountId, int $invoiceId, DateTime $date, string $pdfLink): bool;

    /**
     * @param string $accountId
     * @param int $invoiceId
     * @param string $transactionId
     * @param string $pdfLink
     * @return bool
     */
    public function markPaid(string $accountId, int $invoiceId, string $transactionId, string $pdfLink): bool;

    /**
     * @param string $accountId
     * @param int $invoiceId
     * @param string $pdfLink
     * @return bool
     */
    public function markPending(string $accountId, int $invoiceId, string $pdfLink): bool;

    /**
     * @param string $accountId
     * @param int $invoiceId
     * @param string $pdfLink
     * @return bool
     */
    public function markCancelled(string $accountId, int $invoiceId, string $pdfLink): bool;
}
