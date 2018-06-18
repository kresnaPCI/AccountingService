<?php

namespace App\Transformer;

use App\Model\Invoice;

/**
 * Class InvoiceTransformer
 * @package App\Transformer
 */
class InvoiceTransformer
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
    public function transform(array $data): Invoice
    {
        $invoice = new Invoice();
        $invoice->setAccountId($data['accountId'])
            ->setCurrency($data['currency'])
            ->setCustomerId($data['customerId'])
            ->setInvoiceId($data['invoiceId'])
            ->setInvoiceIncrementId($data['invoiceIncrementId'])
            ->setInvoiceDate(new \DateTime($data['invoiceDate']))
            ->setOrderId($data['orderId'])
            ->setOrderIncrementId($data['orderIncrementId'])
            ->setOrderDate(new \DateTime($data['orderDate']))
            ->setPaymentMethod($data['paymentMethod'])
            ->setPaymentTransactionId($data['paymentTransactionId'])
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
