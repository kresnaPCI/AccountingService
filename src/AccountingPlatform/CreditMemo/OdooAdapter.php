<?php

namespace App\AccountingPlatform\CreditMemo;

use App\AccountingPlatform\Library\Odoo\OdooAccountDataFactory;
use App\AccountingPlatform\Library\Odoo\OdooClient;
use App\AccountingPlatform\Library\Odoo\OdooCommonTrait;
use App\AccountingPlatform\Library\Odoo\OdooRepository;
use App\Model\CreditMemo;
use DateTime;

/**
 * Class OdooAdapter
 * @package App\AccountingPlatform\CreditMemo
 */
class OdooAdapter implements AdapterInterface
{
    use OdooCommonTrait;

    const PARTNER_TYPE = 'customer';
    const PAYMENT_TYPE = 'outbound';
    const INVOICE_TYPE = 'out_refund';

    /**
     * @var OdooClient
     */
    protected $odooClient;

    /**
     * @var OdooAccountDataFactory
     */
    private $dataFactory;

    /**
     * @var OdooRepository
     */
    private $odooRepo;

    /**
     * OdooAdapter constructor.
     * @param OdooClient $odooClient
     */
    public function __construct(OdooClient $odooClient, OdooRepository $odooRepo, OdooAccountDataFactory $dataFactory)
    {
        $this->odooClient = $odooClient;
        $this->dataFactory = $dataFactory;
        $this->odooRepo = $odooRepo;
    }

    /**
     * {@inheritdoc}
     */
    public function create(CreditMemo $creditMemo): bool
    {
        $accountData = $this->dataFactory->getAccountData($creditMemo->getAccountId());

        if ($this->odooRepo->getCreditMemoByMagentoId($accountData->getAccountId(), $creditMemo->getCreditMemoId())) {
            return false;
        }

        // FIRST CREATE INVOICE
        $data = [
            'account_id' => $accountData->getAccountId(),
            'date_invoice' => $creditMemo->getCreditMemoDate()->format('Y-m-d'),
            'pdfurl' => $creditMemo->getPdfUrl(),
            'name' => $creditMemo->getCreditMemoIncrementId(),
            'magento_so' => $creditMemo->getOrderId(),
            'magento_invoice_id' => $creditMemo->getCreditMemoId(),
            'magento_increment_id' => $creditMemo->getOrderIncrementId(), 
        ];

        $fields = ['id', 'active'];

        // Get or Create Customer
        if ($partner = $this->odooRepo->getCustomerByMagentoId($creditMemo->getCustomerId())) {
            $data['partner_id'] = $partner['id'];
        } else {
            $data['partner_id'] = $this->odooRepo->createCustomer(
                $creditMemo->getCustomerId(),
                $creditMemo->getCustomerName(),
                $creditMemo->getCustomerEmail()
            );
        }

        // Get Currency
        if ($currency = $this->odooRepo->getCurrency($creditMemo->getCurrency())) {
            $data['currency_id'] = $currency['id'];
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

        $odooCreditMemoId = $this->odooRepo->createCreditMemo($data);

        // SECOND CREATE INVOICE LINES/INVOICED PRODUCTS
        $getjournal = $this->odooClient->search_read('account.journal', [['id', '=', $journal_id[0]['id']],], ['id', 'default_credit_account_id'], 1);
        $defaultCreditAccountId = $getjournal[0]['default_credit_account_id'][0];

        // Get Default Product
        if ($product = $this->odooRepo->getProduct($accountData->getDefaultProductId())) {
            $productId = $product['id'];
        } else {
            return false;
        }

        // Add Line Items
        foreach ($creditMemo->getLineItems() as $lineItem) {
            $this->addLineItem($lineItem, $odooCreditMemoId, $productId, $defaultCreditAccountId);
        }

        $this->odooClient->methods('account.invoice', 'compute_taxes', $odooCreditMemoId);
        $this->odooClient->methods('account.invoice', 'action_invoice_open', $odooCreditMemoId);

        if ($creditMemo->getStatus() === CreditMemo::STATUS_REFUNDED) {
            // Refresh the Invoice as the value changed
            $odooCreditMemo = $this->odooRepo->getInvoice($odooCreditMemoId);
            // Mark as paid
            $this->updateToPaid($odooCreditMemo, $creditMemo->getRefundTransactionId(), $creditMemo->getRefundMethod());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateDate(string $accountId, int $creditMemoId, DateTime $date, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $creditMemo = $this->odooRepo->getCreditMemoByMagentoId($accountData->getAccountId(), $creditMemoId);

        if (!$creditMemo) {
            return false;
        }

        $this->odooRepo->updateInvoice(
            $creditMemo['id'],
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
    public function markPaid(string $accountId, int $creditMemoId, string $paymentMethod, string $transactionId, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $creditMemo = $this->odooRepo->getCreditMemoByMagentoId($accountData->getAccountId(), $creditMemoId);

        if (!$creditMemo) {
            return false;
        }

        $this->updatePdfUrl($creditMemo, $pdfLink);

        return $this->updateToPaid($creditMemo, $transactionId, $paymentMethod);
    }

    /**
     * {@inheritdoc}
     */
    public function markPending(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $creditMemo = $this->odooRepo->getCreditMemoByMagentoId($accountData->getAccountId(), $creditMemoId);

        if (!$creditMemo) {
            return false;
        }

        $this->updateToPending($creditMemo);
        $this->updatePdfUrl($creditMemo, $pdfLink);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function markCancelled(string $accountId, int $creditMemoId, string $pdfLink): bool
    {
        $accountData = $this->dataFactory->getAccountData($accountId);

        $creditMemo = $this->odooRepo->getCreditMemoByMagentoId($accountData->getAccountId(), $creditMemoId);

        if (!$creditMemo || !in_array($creditMemo['state'], ['open', 'draft'])) {
            return false;
        }

        $this->updateToCancelled($creditMemo);
        $this->updatePdfUrl($creditMemo, $pdfLink);

        return true;
    }
}
