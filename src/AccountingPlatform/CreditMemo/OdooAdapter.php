<?php

namespace App\AccountingPlatform\CreditMemo;

use App\AccountingPlatform\Library\OdooClient;
use App\Model\CreditMemo;
use DateTime;

/**
 * Class OdooAdapter
 * @package App\AccountingPlatform\CreditMemo
 */
class OdooAdapter implements AdapterInterface
{
    /**
     * @var OdooClient
     */
    protected $odooClient;

    /**
     * OdooAdapter constructor.
     * @param OdooClient $odooClient
     */
    public function __construct(OdooClient $odooClient)
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
