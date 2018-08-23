<?php

namespace App\Queue\Processor;

use App\AccountingPlatform\InvoiceService;
use App\Transformer\InvoiceTransformer;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

/**
 * Class CreateInvoiceProcessor
 * @package App\Queue
 */
class CreateInvoiceProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @var InvoiceTransformer
     */
    private $invoiceTransformer;

    /**
     * CreateInvoiceProcessor constructor.
     * @param InvoiceService $invoiceService
     * @param InvoiceTransformer $invoiceTransformer
     */
    public function __construct(InvoiceService $invoiceService, InvoiceTransformer $invoiceTransformer)
    {
        $this->invoiceService = $invoiceService;
        $this->invoiceTransformer = $invoiceTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $messageData = json_decode($message->getBody(), true);

        $invoice = $this->invoiceTransformer->transform($messageData['data']);

        $success = $this->invoiceService->create($invoice);

        return $success ? self::ACK : self::REJECT;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return ['invoice_created'];
    }
}