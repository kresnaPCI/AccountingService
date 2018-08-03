<?php

namespace App\AccountingPlatform\CreditMemo;

use App\Model\CreditMemo;
use DateTime;

/**
 * Interface AdapterInterface
 * @package App\AccountingPlatform\CreditMemo
 */
interface AdapterInterface
{
    /**
     * @param CreditMemo $creditMemo
     * @return bool
     */
    public function create(CreditMemo $creditMemo): bool;

    /**
     * @param string $accountId
     * @param int $creditMemoId
     * @param DateTime $date
     * @param string $pdfLink
     * @return bool
     */
    public function updateDate(string $accountId, int $creditMemoId, DateTime $date, string $pdfLink): bool;

    /**
     * @param string $accountId
     * @param int $creditMemoId
     * @param string $refundMethod
     * @param string $pdfLink
     * @return bool
     */
    public function updateRefundMethod(
        string $accountId,
        int $creditMemoId,
        string $refundMethod,
        string $pdfLink
    ): bool;

    /**
     * @param string $accountId
     * @param int $creditMemoId
     * @param string $method
     * @param string $transactionId
     * @param string $pdfLink
     * @return bool
     */
    public function markPaid(
        string $accountId,
        int $creditMemoId,
        string $method,
        string $transactionId,
        string $pdfLink
    ): bool;

    /**
     * @param string $accountId
     * @param int $creditMemoId
     * @param string $pdfLink
     * @return bool
     */
    public function markPending(string $accountId, int $creditMemoId, string $pdfLink): bool;

    /**
     * @param string $accountId
     * @param int $creditMemoId
     * @param string $pdfLink
     * @return bool
     */
    public function markCancelled(string $accountId, int $creditMemoId, string $pdfLink): bool;
}
