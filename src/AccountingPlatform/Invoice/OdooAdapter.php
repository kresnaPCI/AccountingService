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

    // Odoo server configuration
    

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
        

        $invoice_date = $invoice->getInvoiceDate();
        // $key = key($invoice_date);
        // $date = $invoice_date['$key'];
        $date_invoice = $invoice_date->format('Y-m-d');
        // file_put_contents('print.txt',print_r($date_invoice, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        // FIRST CREATE INVOICE
        $data = [
            'type' => 'out_invoice',
            'account_id' => $invoice->getAccountId(),
            'date_invoice' => $date_invoice,
        ];
        // file_put_contents('print.txt',print_r($data, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        $criteria = [
          ['name', '=ilike', $invoice->getCurrency()],
        ];

        $fields = ['id', 'active'];
        // EACH SEARCH METHOD WE LIMIT ONLY 1 RECORD SO ITS OK TO HARDCODE THE INDEX = 0
        $partner_id = $invoice->getCustomerId();
        $partner = $this->odooClient->search_read('res.partner', [['id', '=', $partner_id],], $fields, 1);
        if (sizeof($partner)>0){
            $data['partner_id'] = $partner[0]['id'];
        }
        else{
            return false;
        }

        $currency = $this->odooClient->search_read('res.currency', $criteria, $fields, 1);
        if (sizeof($currency)){
            $data['currency_id'] = $currency[0]['id'];
        }else{
            return false;
        }

        $id = $this->odooClient->create('account.invoice', $data);
        // SECOND CREATE INVOICE LINES/INVOICED PRODUCTS

        $invoice_id = $this->odooClient->search_read('account.invoice', [['id', '=', $id],], ['id', 'journal_id'], 1);

        $invoice_journal_id = $invoice_id[0]['journal_id'];
        $journal = $this->odooClient->search_read('account.journal', [['display_name', '=', $invoice_journal_id],], ['id', 'default_credit_account_id'], 1);
        $default_credit_account_id = $journal[0]['default_credit_account_id'][0];

        $lineItems = $invoice->getLineItems();
        foreach ($lineItems as $lineItem) {
            // SEARCH PRODUCT BY ITS SKU
            $criteria = [
              ['default_code', '=', $lineItem->getSku()],
            ];

            $fields = ['id', 'description_sale'];

            $product = $this->odooClient->search_read('product.product', $criteria, $fields, 1);

            if (sizeof($product)){
                $product_id = $product[0]['id'];
                $name = $product[0]['description_sale'];
            }else{
                return false;
            }
            // file_put_contents('products.txt',print_r($product, true).PHP_EOL , FILE_APPEND | LOCK_EX);
            $data_line = [
                'invoice_id' => $id,
                'product_id' => $product_id,
                'name' => $name,
                'account_id' => $default_credit_account_id,
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
            if (sizeof($tax)){
                $tax_ids = array(array(6, 0, array($tax[0]['id'])));
            }else{
                return false;
            }
            // IN ODOO, ORDER ITEMS CAN CONTAIN SEVERAL TAXES, SO WE NEED TO ASSIGN THE VALUE AS ARRAY AND ODOO FORMAT WHEN ASSIGNING VALUE TO MANY2MANY FIELD (6, 0, ARRAY OF ID VALUE)
            $data_line['invoice_line_tax_ids'] = $tax_ids;
            $line_id = $this->odooClient->create('account.invoice.line', $data_line);
            
        }
       

        return true;
    }

    public function updateDate(string $accountId, int $invoiceId, DateTime $date, string $pdfLink): bool
    {
        // TODO: Implement updateDate() method.
        $update_invoice_date = $date->format('Y-m-d');
        $data = [
            'date_invoice' => $update_invoice_date,
        ];
        $this->odooClient->write('account.invoice', $invoiceId, $data);
        return true;
    }

    public function markPaid(string $accountId, int $invoiceId, string $transactionId, string $pdfLink, string $status, string $paymentType, string $partnerType): bool
    {
        // TODO: Implement markPaid() method.
        $data = [
            'accountId' => $accountId,
            'invoiceId' => $invoiceId,
            'transactionId' => $transactionId,
            'pdfLink' => $pdfLink,
            'paymentType' => $paymentType,
            'partnerType' => $partnerType
        ];
        file_put_contents('markpaid.txt',print_r($data, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        # ============================= SEARCH DATA INVOICE ============================== 
        $fields = ['id', 'number', 'partner_id', 'account_id', 'date_invoice', 'journal_id', 'state', 'amount_total'];

        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $invoiceId],], $fields, 1);
        $invoice_action = $this->odooClient->methods('account.invoice', 'action_invoice_open', $invoice[0]['id']);
        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $invoiceId],], $fields, 1);

        $invoice_partner_id = $invoice[0]['partner_id'][0];
        $invoice_account_id = $invoice[0]['account_id'][0];
        $invoice_journal_id = $invoice[0]['journal_id'][0];
        $invoice_amount = $invoice[0]['amount_total'];
        $invoice_number = $invoice[0]['number'];
        $payment_date = date('Y-m-d'); # now

        // # ============================= SEARCH DATA ACCOUNT JOURNAL ============================== 
        $payment_journal = $this->odooClient->search_read('account.journal', [['id', '=', $data['transactionId']],], ['id', 'name', 'inbound_payment_method_ids'], 1);
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

    public function cancelInvoice(int $invoiceId): bool
    {
        $invoice = $this->odooClient->search_read('account.invoice', [['id', '=', $invoiceId],['state', 'in', ['open','draft']]]);
        if(empty($invoice)){
            return false;
        }
        $this->odooClient->methods('account.invoice', 'action_invoice_cancel',$invoice[0]['id']);
        return true;
    }


}
