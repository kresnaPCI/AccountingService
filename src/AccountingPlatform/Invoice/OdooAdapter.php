<?php

declare(strict_types=1);

namespace App\AccountingPlatform\Invoice;

use App\Model\Invoice;
use DateTime;
use OdooClient\Client;

/**
 * Class OdooAdapter
 * @package App\AccountingPlatform\Invoice
 */
class OdooAdapter implements AdapterInterface
{
    /**
     * @var Client
     */
    protected $odooClient;

    /**
     * OdooAdapter constructor.
     * @param Client $odooClient
     */
    public function __construct(Client $odooClient)
    {
        $this->odooClient = $odooClient;
    }

    public function create(Invoice $invoice): bool
    {
        // TODO: Implement create() method.
        return true;
    }

    public function updateDate(string $accountId, int $invoiceId, DateTime $date, string $pdfLink): bool
    {
        // TODO: Implement updateDate() method.
        return true;
    }

    public function markPaid(string $accountId, int $invoiceId, string $transactionId, string $pdfLink): bool
    {
        // TODO: Implement markPaid() method.
        return true;
    }

    public function markPending(string $accountId, int $invoiceId, string $pdfLink): bool
    {
        // TODO: Implement markPending() method.
        return true;
    }

    public function markCancelled(string $accountId, int $invoiceId, string $pdfLink): bool
    {
        // TODO: Implement markCancelled() method.
        return true;
    }


}
