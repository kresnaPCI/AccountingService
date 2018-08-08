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
        $creditMemo_date = $creditMemo->getCreditMemoDate();
        // $key = key($invoice_date);
        // $date = $invoice_date['$key'];
        $date_creditmemo = $creditMemo_date->format('Y-m-d');
        // FIRST CREATE INVOICE
        $data = [
            'type' => 'out_refund',
            'account_id' => $creditMemo->getAccountId(),
            'date_invoice' => $date_creditmemo,
            'pdfurl' => $creditMemo->getPdfUrl(),
            'name' => $creditMemo->getOrderIncrementId(),
            'magento_so' => $creditMemo->getOrderId(),
            'magento_invoice_id' => $creditMemo->getCreditMemoId(),
            'magento_increment_id' => $creditMemo->getCreditMemoIncrementId(),
        ];
        $criteria = [
            ['name', '=ilike', $creditMemo->getCurrency()],
        ];

        $fields = ['id', 'active'];
        // EACH SEARCH METHOD WE LIMIT ONLY 1 RECORD SO ITS OK TO HARDCODE THE INDEX = 0
        $partner_id = $creditMemo->getCustomerId();
        $partner = $this->odooClient->search_read('res.partner', [['id', '=', $partner_id],], $fields, 1);

        if (sizeof($partner) > 0) {
            $data['partner_id'] = $partner[0]['id'];
        } else {
            return false;
        }

        $journal_name = $creditMemo->getJournal();
        $journal_id = $this->odooClient->search_read('account.journal', [['name', '=', $journal_name],], $fields, 1);
        if (sizeof($journal_id) > 0) {
            $data['journal_id'] = $journal_id[0]['id'];
        } else {
            trigger_error("you input wrong journal name, please correct it", E_USER_ERROR);
        }

        $currency = $this->odooClient->search_read('res.currency', $criteria, $fields, 1);
        if (sizeof($currency)) {
            $data['currency_id'] = $currency[0]['id'];
        } else {
            return false;
        }

        $id = $this->odooClient->create('account.invoice', $data);
        // SECOND CREATE INVOICE LINES/INVOICED PRODUCTS
        $getjournal = $this->odooClient->search_read('account.journal', [['id', '=', $journal_id[0]['id']],], ['id', 'default_credit_account_id'], 1);
        $default_credit_account_id = $getjournal[0]['default_credit_account_id'];

        $lineItems = $creditMemo->getLineItems();
        foreach ($lineItems as $lineItem) {
            // SEARCH PRODUCT BY ITS SKU
            $criteria = [
                ['default_code', '=', $lineItem->getSku()],
            ];

            $fields = ['id', 'description_sale'];

            $product = $this->odooClient->search_read('product.product', $criteria, $fields, 1);

            if (sizeof($product)) {
                $product_id = $product[0]['id'];
                $name = $product[0]['description_sale'];
            } else {
                return false;
            }
            $data_line = [
                'invoice_id' => $id,
                'product_id' => $product_id,
                'name' => $name,
                'account_id' => $default_credit_account_id[0],
                'quantity' => $lineItem->getQuantity(),
                'discount' => $lineItem->getDiscount(),
                'price_unit' => $lineItem->getUnitPrice(),
            ];
            // SEARCH TAX MASTER DATA
            $criteria = [
                ['name', '=', $lineItem->getTaxIdentifier()],
            ];

            $fields = ['id'];

            $tax = $this->odooClient->search_read('account.tax', $criteria, $fields, 1);
            if (sizeof($tax)) {
                $tax_ids = array(array(6, 0, array($tax[0]['id'])));
            } else {
                return false;
            }
            // IN ODOO, ORDER ITEMS CAN CONTAIN SEVERAL TAXES, SO WE NEED TO ASSIGN THE VALUE AS ARRAY AND ODOO FORMAT WHEN ASSIGNING VALUE TO MANY2MANY FIELD (6, 0, ARRAY OF ID VALUE)
            $data_line['invoice_line_tax_ids'] = $tax_ids;
            $line_id = $this->odooClient->create('account.invoice.line', $data_line);
        }

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

    public function markPaid(
        string $accountId,
        int $creditMemoId,
        string $method,
        string $transactionId,
        string $pdfLink
    ): bool {
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

        $invoice = $this->odooClient->search_read(
            'account.invoice',
            [['id', '=', $creditMemoId]],
            $fields,
            1
        );
        $this->odooClient->methods(
            'account.invoice',
            'action_invoice_open',
            $invoice[0]['id']
        );
        $invoice = $this->odooClient->search_read(
            'account.invoice',
            [['id', '=', $creditMemoId]],
            $fields,
            1
        );

        $invoice_partner_id = $invoice[0]['partner_id'][0];
        $invoice_account_id = $invoice[0]['account_id'][0];
        $invoice_journal_id = $invoice[0]['journal_id'][0];
        $invoice_amount = $invoice[0]['amount_total'];
        $invoice_number = $invoice[0]['number'];
        $payment_date = date('Y-m-d'); # now

        // # ============================= SEARCH DATA ACCOUNT JOURNAL ==============================
        $payment_journal = $this->odooClient->search_read(
            'account.journal',
            [['id', '=', $data['transactionId']],], ['id', 'name', 'inbound_payment_method_ids'],
            1
        );
        file_put_contents(
            'markpaid_payment_method.txt',
            print_r($payment_journal, true) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
        $payment_method = $payment_journal[0]['inbound_payment_method_ids'][0];

        // # ============================= CREATE PAYMENT ==============================
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

        $this->odooClient->methods(
            'account.payment',
            'action_validate_invoice_payment',
            $payment
        );
        $payment_account = $this->odooClient->search_read('account.payment', [['id', '=', $payment],]);

        $this->odooClient->methods(
            'account.payment',
            'post',
            $payment_account[0]['id']
        );

        return true;
    }

    public function markPending(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        // TODO: Implement markPending() method.
        return true;
    }

    public function markCancelled(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        $creditMemo = $this->odooClient->search_read(
            'account.invoice',
            [['id', '=', $creditMemoId], ['state', 'in', ['open', 'draft']]]
        );

        if (empty($creditMemo)) {
            return false;
        }

        $this->odooClient->methods('account.invoice', 'action_invoice_cancel', $creditMemo[0]['id']);

        return true;
    }
}
