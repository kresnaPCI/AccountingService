<?php

namespace App\Transformer;

use App\Model\CreditMemo;

/**
 * Class CreditMemoTransformer
 * @package App\Transformer
 */
class CreditMemoTransformer
{
    /**
     * @var LineItemTransformer
     */
    protected $lineItemTransformer;

    /**
     * InvoiceTransformer constructor.
     * @param LineItemTransformer $lineItemTransformer
     */
    public function __construct(LineItemTransformer $lineItemTransformer)
    {
        $this->lineItemTransformer = $lineItemTransformer;
    }

    /**
     * @param array $data
     * @return CreditMemo
     */
    public function transform(array $data): CreditMemo
    {
        $invoice = new CreditMemo();
        $invoice->setAccountId($data['accountId'])
            ->setCurrency($data['currency'])
            ->setCustomerId($data['customerId'])
            ->setCreditMemoId($data['creditMemoId'])
            ->setCreditMemoIncrementId($data['creditMemoIncrementId'])
            ->setCreditMemoDate(new \DateTime($data['creditMemoDate']))
            ->setOrderId($data['orderId'])
            ->setOrderIncrementId($data['orderIncrementId'])
            ->setRefundMethod($data['refundMethod'])
            ->setRefundTransactionId($data['refundTransactionId'])
            ->setJournal($data['journalName'])
            ->setPdfUrl($data['pdfUrl'])
            ->setStatus($data['status']);
        
        $lineItems = [];
        foreach ($data['lineItems'] as $line) {
            $lineItems[] = $this->lineItemTransformer->transform($line);
        }

        $invoice->setLineItems($lineItems);
        
        return $invoice;
    }
}
