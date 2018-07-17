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
        // $creditMemo_date = $creditMemo->getInvoiceDate();
        // // $key = key($invoice_date);
        // // $date = $invoice_date['$key'];
        // $date_creditMemo = $creditMemoe_date->format('Y-m-d');
        
        // // FIRST CREATE INVOICE
        // $data = [
        //     'type' => 'out_invoice',
        //     'account_id' => $creditMemo->getAccountId(),
        //     'date_invoice' => $date_creditMemo,
        // ];
        file_put_contents('markpaid.txt',print_r($creditMemo, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        return true;
        return true;
    }

    public function updateDate(string $accountId, int $creditMemoId, DateTime $date, string $pdfLink): bool
    {
        // TODO: Implement updateDate() method.
        return true;
    }

    public function updateRefundMethod(
        string $accountId,
        int $creditMemoId,
        string $refundMethod,
        string $pdfLink
    ): bool {
        // TODO: Implement updateRefundMethod() method.
        return true;
    }

    public function markPaid(string $accountId, int $creditMemoId, string $transactionId, string $pdfLink): bool
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
