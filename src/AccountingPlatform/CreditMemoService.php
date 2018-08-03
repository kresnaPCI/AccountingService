<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15/6/2018 AD
 * Time: 14:01
 */

namespace App\AccountingPlatform;

use App\AccountingPlatform\CreditMemo\AdapterInterface;
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
     * @param CreditMemo $creditMemo
     * @return bool
     */
    public function create(CreditMemo $creditMemo): bool
    {
        return $this->adapter->create($creditMemo);
    }

}
