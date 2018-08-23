<?php

declare(strict_types=1);

namespace App\AccountingPlatform\Invoice;

use App\AccountingPlatform\Library\Odoo\OdooAccountDataFactory;
use App\AccountingPlatform\Library\Odoo\OdooClient;
use App\AccountingPlatform\Library\Odoo\OdooCommonTrait;
use App\AccountingPlatform\Library\Odoo\OdooRepository;
use App\Model\Invoice;
use DateTime;

/**
 * Class OdooAdapter
 * @package App\AccountingPlatform\Invoice
 */
class OdooAdapter implements AdapterInterface
{
    use OdooCommonTrait;

    const PARTNER_TYPE = 'customer';
    const PAYMENT_TYPE = 'inbound';
    const INVOICE_TYPE = 'out_invoice';

    /**
     * @var OdooClient
     */
    private $odooClient;

    /**
     * @var OdooRepository
     */
    private $odooRepo;

    /**
     * @var OdooAccountDataFactory
     */
    private $dataFactory;

    /**
     * OdooAdapter constructor.
     * @param OdooClient $odooClient
     * @param OdooRepository $odooRepo
     * @param OdooAccountDataFactory $dataFactory
     */
    public function __construct(OdooClient $odooClient, OdooRepository $odooRepo, OdooAccountDataFactory $dataFactory)
    {
        $this->odooClient = $odooClient;
        $this->odooRepo = $odooRepo;
        $this->dataFactory = $dataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Invoice $invoice): bool
    {
        $accountData = $this->dataFactory->getAccountData($invoice->getAccountId());

        if ($this->odooRepo->getInvoiceByMagentoId($accountData->getAccountId(), $invoice->getInvoiceId())){
            return false;
        }

        // FIRST CREATE INVOICE
        $data = [
            'type' => 'out_invoice',
            'account_id' => $accountData->getAccountId(),
            'date_invoice' => $invoice->getInvoiceDate()->format('Y-m-d'),
            'pdfurl' => $invoice->getPdfUrl(),
            'magento_increment_id' => $invoice->getOrderIncrementId(),
            'magento_so' => $invoice->getOrderId(),
            'magento_invoice_id' => $invoice->getInvoiceId(),
            'name' => $invoice->getInvoiceIncrementId(),
        ];


        // Get or Create Customer
        if ($partner = $this->odooRepo->getCustomerByMagentoId($invoice->getCustomerId())) {
            $data['partner_id'] = $partner['id'];
        } else {
            $data['partner_id'] = $this->odooRepo->createCustomer(
                $invoice->getCustomerId(),
                $invoice->getCustomerName(),
                $invoice->getCustomerEmail()
            );
        }

        // Get Currency
        if ($currency = $this->odooRepo->getCurrency($invoice->getCurrency())) {
            $data['currency_id'] = $currency['id'];
        } else {
            return false;
        }
        // Create and Fetch Invoice
        $odooInvoiceId = $this->odooRepo->createInvoice($data);
        $odooInvoice = $this->odooRepo->getInvoice($odooInvoiceId);

        // Get Journal
        $invoice_journal_id = $odooInvoice['journal_id'];
        $journal = $this->odooClient->search_read(
            'account.journal',
            [['display_name', 'in', $invoice_journal_id]], ['id', 'default_credit_account_id'],
            1
        );
        $defaultCreditAccountId = $journal[0]['default_credit_account_id'][0];

        // Get Default Product
        if ($product = $this->odooRepo->getProduct($accountData->getDefaultProductId())) {
            $productId = $product['id'];
        } else {
            return false;
        }

        // Create Line Items
        foreach ($invoice->getLineItems() as $lineItem) {
            $this->addLineItem($lineItem, $odooInvoiceId, $productId, $defaultCreditAccountId);
        }
      
        $this->odooClient->methods('account.invoice', 'compute_taxes', $odooInvoiceId);
        $this->odooClient->methods('account.invoice', 'action_invoice_open', $odooInvoiceId);

        if ($invoice->getStatus() === Invoice::STATUS_PAID){
            // Refresh the Invoice as the value changed
            $odooInvoice = $this->odooRepo->getInvoice($odooInvoiceId);
            // Mark as paid
            $this->updateToPaid($odooInvoice, $invoice->getPaymentTransactionId(), $invoice->getPaymentMethod());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateDate(string $accountId, int $invoiceId, DateTime $date, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $invoice = $this->odooRepo->getInvoiceByMagentoId($accountData->getAccountId(), $invoiceId);

        if (!$invoice) {
            return false;
        }

        $this->odooRepo->updateInvoice(
            $invoice['id'],
            [
                'date_invoice' => $date->format('Y-m-d'),
                'pdfurl' => $pdfLink,
            ]
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function markPaid(string $accountId, int $invoiceId, string $transactionId, string $paymentMethod, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $invoice = $this->odooRepo->getInvoiceByMagentoId($accountData->getAccountId(), $invoiceId);
        if (!$invoice){
            return false;
        }

        $this->updatePdfUrl($invoice, $pdfLink);

        return $this->updateToPaid($invoice, $transactionId, $paymentMethod);
    }

    /**
     * {@inheritdoc}
     */
    public function markPending(string $accountId, int $invoiceId, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $invoice = $this->odooRepo->getInvoiceByMagentoId($accountData->getAccountId(), $invoiceId);

        if (!$invoice || !in_array($invoice['state'], [Invoice::STATUS_PAID])) {
            return false;
        }

        $this->updateToPending($invoice);
        $this->updatePdfUrl($invoice, $pdfLink);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function markCancelled(string $accountId, int $invoiceId, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);


        $invoice = $this->odooRepo->getInvoiceByMagentoId($accountData->getAccountId(), $invoiceId);

        if (!$invoice || !in_array($invoice['state'], ['open', 'draft', 'paid'])) {
            return false;
        }

        if ($invoice['state'] == 'paid') {
            $this->updateToPending($invoice);
        }

        $this->updateToCancelled($invoice);
        $this->updatePdfUrl($invoice, $pdfLink);

        return true;
    }
}
