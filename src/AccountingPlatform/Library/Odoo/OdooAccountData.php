<?php

namespace App\AccountingPlatform\Library\Odoo;

/**
 * Class OdooAccountData
 * @package App\AccountingPlatform\Library\Odoo
 */
class OdooAccountData
{
    /**
     * @var array
     */
    protected $config;

    /**
     * OdooAccountData constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return (int)$this->config['account_id'];
    }

    /**
     * @return int
     */
    public function getDefaultProductId()
    {
        return (int)$this->config['product_id'];
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->config['prefix'];
    }

    /**
     * @param string $value
     * @return string
     */
    public function addPrefix(string $value)
    {
        return $this->getPrefix() . '-' . $value;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->config['timezone'];
    }
}