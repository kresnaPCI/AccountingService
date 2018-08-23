<?php

namespace App\Queue\Processor;

use App\AccountingPlatform\InvoiceService;
use App\Command\Invoice\UpdateDateCommand;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

/**
 * Class ChangeInvoiceDateProcessor
 * @package App\Queue\Processor
 */
class UpdateInvoiceDateProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * PayInvoiceProcessor constructor.
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $messageData = json_decode($message->getBody(), true);

        $data = $messageData['data'];

        $command = new UpdateDateCommand(
            $data['accountId'],
            $data['invoiceId'],
            new \DateTime($data['invoiceDate']),
            $data['pdfUrl']
        );

        $success = $this->invoiceService->updateDate($command);

        return $success ? self::ACK : self::REJECT;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return ['invoice_date_updated'];
    }
}