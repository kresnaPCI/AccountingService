<?php

namespace App\AccountingPlatform;

use App\AccountingPlatform\Invoice\AdapterInterface;
use App\Command\Invoice\MarkPaidCommand;
use App\Command\Invoice\UpdateDateCommand;
use App\Command\Invoice\UpdateStatusCommand;
use App\Model\Invoice;

/**
 * Class InvoiceService
 * @package App\Service
 */
class InvoiceService
{
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * InvoiceService constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function create(Invoice $invoice): bool
    {
        return $this->adapter->create($invoice);
    }

    /**
     * @param UpdateDateCommand $command
     * @return bool
     */
    public function updateDate(UpdateDateCommand $command): bool
    {
        return $this->adapter->updateDate(
            $command->getAccountId(),
            $command->getInvoiceId(),
            $command->getDate(),
            $command->getPdfUrl()
        );
    }

    /**
     * @param MarkPaidCommand $command
     * @return bool
     */
    public function markPaid(MarkPaidCommand $command): bool
    {
        return $this->adapter->markPaid(
            $command->getAccountId(),
            $command->getInvoiceId(),
            $command->getTransactionId(),
            $command->getPdfUrl()
        );
    }

    /**
     * @param UpdateStatusCommand $command
     * @return bool
     */
    public function updateStatus(UpdateStatusCommand $command): bool
    {
        switch ($command->getStatus()) {
            case self::STATUS_PENDING:
                return $this->adapter->markPending(
                    $command->getAccountId(),
                    $command->getInvoiceId(),
                    $command->getPdfUrl()
                );
                break;
            case self::STATUS_CANCELLED:
                return $this->adapter->markCancelled(
                    $command->getAccountId(),
                    $command->getInvoiceId(),
                    $command->getPdfUrl()
                );
                break;
        }

        return false;
    }
}
