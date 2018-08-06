<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15/6/2018 AD
 * Time: 14:01
 */

namespace App\AccountingPlatform;

use App\AccountingPlatform\CreditMemo\AdapterInterface;
use App\Command\CreditMemo\RefundCommand;
use App\Command\CreditMemo\UpdateDateCommand;
use App\Command\CreditMemo\UpdateStatusCommand;
use App\Model\CreditMemo;

/**
 * Class CreditMemoService
 * @package App\Service
 */
class CreditMemoService
{
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * CreditMemoService constructor.
     * @param AdapterInterface $accountingAdapter
     */
    public function __construct(AdapterInterface $accountingAdapter)
    {
        $this->adapter = $accountingAdapter;
    }

    /**
     * @param CreditMemo $creditMemo
     * @return bool
     */
    public function create(CreditMemo $creditMemo): bool
    {
        return $this->adapter->create($creditMemo);
    }

    /**
     * @param RefundCommand $command
     * @return bool
     */
    public function refund(RefundCommand $command): bool
    {
        return $this->adapter->markPaid(
            $command->getAccountId(),
            $command->getInvoiceId(),
            $command->getMethod(),
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
                    $command->getCreditMemoId(),
                    $command->getPdfUrl()
                );
                break;
            case self::STATUS_CANCELLED:
                return $this->adapter->markCancelled(
                    $command->getAccountId(),
                    $command->getCreditMemoId(),
                    $command->getPdfUrl()
                );
                break;
        }

        return false;
    }

    /**
     * @param UpdateDateCommand $command
     * @return bool
     */
    public function updateDate(UpdateDateCommand $command): bool
    {
        return $this->adapter->updateDate(
            $command->getAccountId(),
            $command->getCreditMemoId(),
            $command->getDate(),
            $command->getPdfUrl()
        );
    }
}
