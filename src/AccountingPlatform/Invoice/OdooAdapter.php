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
        $this->odooHost = getenv('ODOO_HOST');
        $this->odooDb = getenv('ODOO_DB');
        $this->odooUser = getenv('ODOO_USER');
        $this->odooPass = getenv('ODOO_PASS');

        $this->client = new Client($this->odooHost, $this->odooDb, $this->odooUser, $this->odooPass);
    }

    public function create(Invoice $invoice): bool
    {
        // TODO: Implement create() method.
        
        
        // FIRST CREATE INVOICE
        $data = [
            'partner_id' => $invoice->getCustomerId(),
            'type' => 'out_invoice',
            'account_id' => $invoice->getAccountId(),
            
        ];
        
        $criteria = [
          ['name', '=ilike', $invoice->getCurrency()],
        ];

        $fields = ['id', 'active'];
        // EACH SEARCH METHOD WE LIMIT ONLY 1 RECORD SO ITS OK TO HARDCODE THE INDEX = 0
        $currency = $this->client->search_read('res.currency', $criteria, $fields, 1);
        
        if (isset($currency){
            $data['currency_id'] => $currency[0]['id']
        }
        $id = $this->client->create('account.invoice', $data);
        // SECOND CREATE INVOICE LINES/INVOICED PRODUCTS
        // file_put_contents('invoice_object.txt',print_r($id, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        $lineItems = $invoice->getLineItems();
        foreach ($lineItems as $lineItem) {
            // SEARCH PRODUCT BY ITS SKU
            $criteria = [
              ['default_code', '=', $lineItem->getSku()],
            ];

            $fields = ['id', 'description_sale'];

            $product = $this->client->search_read('product.product', $criteria, $fields, 1);
            // file_put_contents('products.txt',print_r($product, true).PHP_EOL , FILE_APPEND | LOCK_EX);
            $data_line = [
                'invoice_id' => $id,
                'product_id' => $product[0]['id'],
                'name' => $product[0]['description_sale'],
                'account_id' => 17,
                'quantity' => $lineItem->getQuantity(),
                'discount' => $lineItem->getDiscount(),
                'price_unit' => $lineItem->getUnitPrice(),
                'invoice_line_tax_ids' => array(array(6, 0, array(24))),
            ];
            // SEARCH TAX MASTER DATA
            $criteria = [
              ['name', '=', $lineItem->getTaxIdentifier()],
            ];

            $fields = ['id'];

            $tax = $this->client->search_read('account.tax', $criteria, $fields, 1);
            // IN ODOO, ORDER ITEMS CAN CONTAIN SEVERAL TAXES, SO WE NEED TO ASSIGN THE VALUE AS ARRAY AND ODOO FORMAT WHEN ASSIGNING VALUE TO MANY2MANY FIELD (6, 0, ARRAY OF ID VALUE)
            $data_line['invoice_line_tax_ids'] = array(array(6, 0, array($tax[0]['id'])));
            $line_id = $this->client->create('account.invoice.line', $data_line);
            
        }
       
        file_put_contents('invoice_id.txt',print_r($id, true).PHP_EOL , FILE_APPEND | LOCK_EX);
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
