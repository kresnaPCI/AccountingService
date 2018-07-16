<?php

namespace App\AccountingPlatform;

use App\AccountingPlatform\Invoice\AdapterInterface;
use App\Command\Invoice\UpdateDateCommand;
use App\Command\Invoice\UpdateStatusCommand;
use App\Model\Invoice;

/**
 * Class InvoiceService
 * @package App\Service
 */
class InvoiceService
{
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

    public function markPaid(): bool
    {
        return $this->adapter->markPaid($invoice);
    }

    public function markPending(): bool
    {
        //
    }

    public function markCancelled(): bool
    {
        //
    }
}
