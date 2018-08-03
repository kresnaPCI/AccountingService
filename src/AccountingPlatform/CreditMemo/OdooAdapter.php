<?php

namespace App\AccountingPlatform\CreditMemo;

use App\Model\CreditMemo;
use DateTime;
use OdooClient\Client;

/**
 * Class OdooAdapter
 * @package App\AccountingPlatform\CreditMemo
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

    public function create(CreditMemo $creditMemo): bool
    {
        // TODO: Implement create() method.
        return true;
    }

    public function updateDate(string $accountId, int $creditMemoId, DateTime $date, string $pdfLink): bool
    {
        // TODO: Implement updateDate() method.
        return true;
    }

    public function markPaid(string $accountId, int $creditMemoId, string $method, string $transactionId, string $pdfLink): bool
    {
        // TODO: Implement markPaid() method.
        return true;
    }

    public function markPending(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        // TODO: Implement markPending() method.
        return true;
    }

    public function markCancelled(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        // TODO: Implement markCancelled() method.
        return true;
    }
}
