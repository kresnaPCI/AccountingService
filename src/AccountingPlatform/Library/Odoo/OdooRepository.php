<?php

namespace App\AccountingPlatform\Library\Odoo;

/**
 * Class OdooRepository
 * @package App\AccountingPlatform\Library
 */
class OdooRepository
{
    const TYPE_INVOICE = 'out_invoice';
    const TYPE_CREDITMEMO = 'out_refund';

    /**
     * @var OdooClient
     */
    private $odooClient;

    /**
     * OdooRepository constructor.
     * @param OdooClient $odooClient
     */
    public function __construct(OdooClient $odooClient)
    {
        $this->odooClient = $odooClient;
    }

    /**
     * @param array $data
     * @return int
     */
    public function createCreditMemo(array $data)
    {
        $data['type'] = self::TYPE_CREDITMEMO;

        return $this->odooClient->create('account.invoice', $data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function createInvoice(array $data)
    {
        $data['type'] = self::TYPE_INVOICE;

        return $this->odooClient->create('account.invoice', $data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function createLineItem(array $data)
    {
        return $this->odooClient->create('account.invoice.line', $data);
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $email
     * @return int
     */
    public function createCustomer(string $id, string $name, string $email)
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'magento_id' => $id
        ];

        if (strpos($id, 'company') !== false) {
            $data['company_type'] = 'company';
        }

        return $this->odooClient->create('res.partner', $data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function createPayment(array $data)
    {
        return $this->odooClient->create('account.payment', $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateInvoice(int $id, array $data)
    {
        return $this->odooClient->write('account.invoice', $id, $data);
    }

    /**
     * @param int $id
     * @return null|array
     */
    public function getInvoice(int $id)
    {
        $results = $this->odooClient->search_read(
            'account.invoice',
            [['id', '=', $id]],
            [
                'id',
                'magento_invoice_id',
                'number',
                'partner_id',
                'account_id',
                'date_invoice',
                'journal_id',
                'state',
                'amount_total'
            ],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $accountId
     * @param int $magentoId
     * @return null|array
     */
    public function getInvoiceByMagentoId(int $accountId, int $magentoId)
    {
        $results = $this->odooClient->search_read(
            'account.invoice',
            [
                ['magento_invoice_id', '=', $magentoId],
                ['account_id', '=', $accountId],
                ['type', '=', self::TYPE_INVOICE]
            ],
            [
                'id',
                'number',
                'move_id',
                'partner_id',
                'account_id',
                'date_invoice',
                'journal_id',
                'state',
                'amount_total'
            ],
            1
        );
        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param int $id
     * @return null|array
     */
    public function getCreditMemo(int $id)
    {
        $results = $this->odooClient->search_read(
            'account.invoice',
            [['id', '=', $id], ['type', '=', self::TYPE_CREDITMEMO]],
            [
                'id',
                'magento_invoice_id',
                'number',
                'partner_id',
                'account_id',
                'date_invoice',
                'journal_id',
                'state',
                'amount_total'
            ],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $accountId
     * @param int $magentoId
     * @return null|array
     */
    public function getCreditMemoByMagentoId(int $accountId, int $magentoId)
    {
        $results = $this->odooClient->search_read(
            'account.invoice',
            [
                ['magento_invoice_id', '=', $magentoId],
                ['account_id', '=', $accountId],
                ['type', '=', self::TYPE_CREDITMEMO]
            ],
            [
                'id',
                'number',
                'move_id',
                'partner_id',
                'account_id',
                'date_invoice',
                'journal_id',
                'state',
                'amount_total'
            ],
            1
        );
        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $currencyCode
     * @return null|array
     */
    public function getCurrency(string $currencyCode)
    {
        $results = $this->odooClient->search_read(
            'res.currency',
            [['name', '=ilike', $currencyCode]],
            ['id', 'name', 'active'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $customerId
     * @return null|array
     */
    public function getCustomer(string $customerId)
    {
        $results = $this->odooClient->search_read(
            'res.partner',
            [['id', '=', $customerId]],
            ['id', 'active'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $customerId
     * @return null|array
     */
    public function getCustomerByMagentoId(string $customerId)
    {
        $results = $this->odooClient->search_read(
            'res.partner',
            [['magento_id', '=', $customerId]],
            ['id', 'magento_id', 'name', 'email', 'active'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $productId
     * @return null|array
     */
    public function getProduct(int $productId)
    {
        $results = $this->odooClient->search_read(
            'product.product',
            [['id', '=', $productId]],
            ['id', 'description_sale'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $name
     * @return null|array
     */
    public function getJournalByName(string $name)
    {
        $results = $this->odooClient->search_read(
            'account.journal',
            [['name', '=', $name]],
            ['id', 'name', 'inbound_payment_method_ids'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param string $taxId
     * @return null|array
     */
    public function getTaxByName(string $taxId)
    {
        $results = $this->odooClient->search_read(
            'account.tax',
            [['name', '=', $taxId]],
            ['id'],
            1
        );

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }
}