<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15/6/2018 AD
 * Time: 14:01
 */

namespace App\AccountingPlatform;

use App\AccountingPlatform\CreditMemo\AdapterInterface;
use App\Command\CreditMemo\UpdateDateCommand;
use App\Command\CreditMemo\UpdateRefundMethodCommand;
use App\Command\CreditMemo\UpdateStatusCommand;
use App\Model\CreditMemo;

/**
 * Class CreditMemoService
 * @package App\Service
 */
class CreditMemoService
{
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
     * @param Invoice $invoice
     * @return bool
     */
    public function create(CreditMemo $creditMemo): bool
    {
        return $this->adapter->create($creditMemo);
    }


     /**
     * @param UpdateDateCommand $command
     * @return bool
     */
    public function updateDate(UpdateDateCommand $command): bool
    {
        return $this->adapter->updateDate(
            $command->getAccountId(),
            $command->creditMemoId(),
            $command->getDate(),
            $command->getPdfUrl()
        );
    }
    
    /**
     * @param UpdateStatusCommand $command
     * @return bool
     */
    public function markPaid(UpdateStatusCommand $command): bool
    {
        return $this->adapter->markPaid(
            $command->getAccountId(),
            $command->getcreditMemoId(),
            $command->getStatus(),
            $command->getPdfUrl(),
            $command->getTransactionId(),
            $command->getPaymentType(),
            $command->getPartnerType()
        );
    }

    public function cancelCreditMemo(int $creditMemoId): bool
    {
        return $this->adapter->cancelCreditMemo($creditMemoId);
    }
}
