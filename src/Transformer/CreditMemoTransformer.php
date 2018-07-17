<?php

namespace App\Transformer;

use App\Model\CreditMemo;

/**
 * Class InvoiceTransformer
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
     * @return Invoice
     */
    public function transform(array $data): CreditMemo
    {
        $CreditMemo = new CreditMemo();
        $CreditMemo->setAccountId($data['accountId'])
            ->setCurrency($data['currency'])
            ->setPdfUrl($data['pdfUrl'])
            ->setStatus($data['status'])
            ->setCustomerId($data['customerId'])
            ->setOrderId($data['orderId'])
            ->setOrderIncrementId($data['orderIncrementId'])
            ->setCreditMemoDate(new \DateTime($data['creditMemoDate']))
            ->setCreditMemoId($data['creditMemoId'])
            ->setCreditMemoIncrementId($data['creditMemoIncrementId'])
            ->setRefundMethod($data['refundMethod'])
            ->setRefundTransactionId($data['refundTransactionId']);
        
        $lineItems = [];
        foreach ($data['lineItems'] as $line) {
            $lineItems[] = $this->lineItemTransformer->transform($line);
        }

        $CreditMemo->setLineItems($lineItems);
        
        return $CreditMemo;
    }
}
