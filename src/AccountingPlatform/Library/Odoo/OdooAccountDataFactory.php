<?php

namespace App\AccountingPlatform\Library\Odoo;

/**
 * Class OdooAccountDataFactory
 * @package App\AccountingPlatform\Library\Odoo
 */
class OdooAccountDataFactory
{
    /**
     * @var array
     */
    protected $config;

    /**
     * OdooAccountDataFactory constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $account
     * @return OdooAccountData|null
     */
    public function getAccountData($account)
    {
        if (!isset($this->config[$account])) {
            return null;
        }

        return new OdooAccountData($this->config[$account]);
    }
}