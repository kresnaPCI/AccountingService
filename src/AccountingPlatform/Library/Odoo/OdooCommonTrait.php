<?php

namespace App\AccountingPlatform\Library\Odoo;

use App\Model\LineItem;

/**
 * Trait OdooCommonTrait
 * @package App\AccountingPlatform\Library\Odoo
 */
trait OdooCommonTrait
{
    /**
     * @var OdooClient $odooClient
     * @var OdooRepository $odooClient
     */

    /**
     * @param array $item
     * @return bool
     */
    protected function updateToPending(array $item): bool
    {
        # JOURNAL ENTRY
        $accountMove = $this->odooClient->search_read(
            'account.move',
            [['id', '=', $item['move_id'][0]]],
            ['id', 'line_ids']
        );

        # JOURNAL ITEMS
        foreach ($accountMove[0]['line_ids'] as $moveLineId) {
            $accountMoveLine = $this->odooClient->search_read(
                'account.move.line',
                [['id', '=', $moveLineId]],
                ['id']
            );
            $this->odooClient->methods(
                'account.move.line',
                'remove_move_reconcile',
                $accountMoveLine[0]['id']
            );
        }

        return true;
    }

    /**
     * @param LineItem $lineItem
     * @param int $parentId
     * @param $defaultProductId
     * @param $creditAccountId
     * @return bool
     */
    protected function addLineItem(LineItem $lineItem, int $parentId, $defaultProductId, $creditAccountId)
    {
        $productName = ucwords(str_replace('_', ' ', $lineItem->getSku()));

        $dataLine = [
            'invoice_id' => $parentId,
            'product_id' => $defaultProductId,
            'name' => $productName,
            'gogoprint_sku' => $lineItem->getSku(),
            'account_id' => $creditAccountId,
            'quantity' => $lineItem->getQuantity(),
            'discount' => $lineItem->getDiscount(),
            'price_unit' => $lineItem->getUnitPrice(),
        ];

        // Get the Tax
        if ($tax = $this->odooRepo->getTaxByName($lineItem->getTaxIdentifier())) {
            // IN ODOO, ORDER ITEMS CAN CONTAIN SEVERAL TAXES, SO WE NEED TO ASSIGN THE VALUE AS ARRAY AND
            // ODOO FORMAT WHEN ASSIGNING VALUE TO MANY2MANY FIELD (6, 0, ARRAY OF ID VALUE)
            $dataLine['invoice_line_tax_ids'] = [[6, 0, [$tax['id']]]];
        } else {
            return false;
        }

        return $this->odooRepo->createLineItem($dataLine);
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function updateToCancelled(array $item)
    {
        return $this->odooClient->methods(
            'account.invoice',
            'action_invoice_cancel',
            $item['id']
        );
    }

    /**
     * @param array $item
     * @param string $pdfUrl
     * @return array
     */
    protected function updatePdfUrl(array $item, string $pdfUrl)
    {
        return $this->odooRepo->updateInvoice($item['id'], ['pdfurl' => $pdfUrl]);
    }


    /**
     * @param array $item
     * @param string $transactionId
     * @param string $paymentMethod
     * @return bool
     */
    private function updateToPaid(array $item, string $transactionId, string $paymentMethod)
    {
        $paymentDate = date('Y-m-d'); # now

        // # ============================= SEARCH DATA ACCOUNT JOURNAL ==============================
        $paymentJournal = $this->odooRepo->getJournalByName($paymentMethod);

        if (!$paymentJournal) {
            $paymentJournal = $this->odooRepo->getJournalByName('Bank');
        }

        $paymentMethod = $paymentJournal['inbound_payment_method_ids'][0];

        // # ============================= CREATE PAYMENT ==============================
        $dataPayment = [
            'payment_date' => $paymentDate,
            'payment_method_id' => $paymentMethod,
            'communication' => $item['number'],
            'invoice_ids' => [[4, $item['id'], 0]],
            'amount' => $item['amount_total'],
            'payment_type' => self::PAYMENT_TYPE,
            'partner_type' => self::PARTNER_TYPE,
            'partner_id' => $item['partner_id'][0],
            'journal_id' => $paymentJournal['id'],
            'magento_transaction_id' => $transactionId,
        ];

        $paymentId = $this->odooRepo->createPayment($dataPayment);

        $this->odooClient->methods('account.payment', 'action_validate_invoice_payment', $paymentId);
        $this->odooClient->methods('account.payment', 'post', $paymentId);

        return true;
    }
}