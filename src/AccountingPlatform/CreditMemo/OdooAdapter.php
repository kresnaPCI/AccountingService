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
        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $creditMemo->getInvoiceId()],['state', 'not in', ['draft','cancel']]]);
        if(empty($invoice)){
            return false;
        }
        $this->odooClient->methods('account.invoice', 'refund', $invoice[0]['id']);
        return true;
    }

    public function updateDate(string $accountId, int $creditMemoId, DateTime $date, string $pdfLink): bool
    {
        // TODO: Implement updateDate() method.
        $update_invoice_date = $date->format('Y-m-d');
        $data = [
            'date_invoice' => $update_invoice_date,
        ];
        $this->odooClient->write('account.invoice', $creditMemoId, $data);
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

    public function markPaid(string $accountId, int $creditMemoId, string $status, string $pdfUrl, string $transactionId, string $paymentType, string $partnerType): bool
    {
        // TODO: Implement markPaid() method.
        $data = [
            'accountId' => $accountId,
            'creditMemoId' => $creditMemoId,
            'status' => $status,
            'transactionId' => $transactionId,
            'pdfLink' => $pdfUrl,
            'paymentType' => $paymentType,
            'partnerType' => $partnerType
        ];
        // ============================= SEARCH DATA INVOICE ============================== 
        $fields = ['id', 'number', 'partner_id', 'account_id', 'date_invoice', 'journal_id', 'state', 'amount_total'];

        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $creditMemoId],], $fields, 1);
        $invoice_action = $this->odooClient->methods('account.invoice', 'action_invoice_open', $invoice[0]['id']);
        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $creditMemoId],], $fields, 1);

        $invoice_partner_id = $invoice[0]['partner_id'][0];
        $invoice_account_id = $invoice[0]['account_id'][0];
        $invoice_journal_id = $invoice[0]['journal_id'][0];
        $invoice_amount = $invoice[0]['amount_total'];
        $invoice_number = $invoice[0]['number'];
        $payment_date = date('Y-m-d'); # now

        // # ============================= SEARCH DATA ACCOUNT JOURNAL ============================== 
        $payment_journal = $this->odooClient->search_read('account.journal', [['id', '=', $data['transactionId']],], ['id', 'name', 'inbound_payment_method_ids'], 1);
        file_put_contents('markpaid_payment_method.txt',print_r($payment_journal, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        $payment_method = $payment_journal[0]['inbound_payment_method_ids'][0];

        // # ============================= CREAT PAYMENT ============================== 
        $data_payment = [
            'payment_date' => $payment_date,
            'payment_method_id' => $payment_method,
            'communication' => $invoice_number,
            'invoice_ids' => array(array(4, $invoice[0]['id'], 0)),
            'amount' => $invoice_amount,
            'payment_type' => $data['paymentType'],
            'partner_type' => $data['partnerType'],
            'partner_id' => $invoice_partner_id,
            'journal_id' => $accountId,
        ];
        $payment = $this->odooClient->create('account.payment', $data_payment);

        $payment_action = $this->odooClient->methods('account.payment', 'action_validate_invoice_payment', $payment);
        $payment_account= $this->odooClient->search_read('account.payment', [['id', '=', $payment],]);

        $payment_action_post = $this->odooClient->methods('account.payment', 'post', $payment_account[0]['id']);
        
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

    public function cancelCreditMemo(int $creditMemoId): bool
    {
        $creditmemo = $this->odooClient->search_read('account.invoice', [['id', '=', $creditMemoId],['state', 'in', ['open','draft']]]);
        if(empty($creditmemo)){
            return false;
        }
        $this->odooClient->methods('account.invoice', 'action_invoice_cancel',$creditmemo[0]['id']);
        return true;
    }


}
