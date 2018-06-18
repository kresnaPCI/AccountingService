<?php

namespace App\Model;

/**
 * Class LineItem
 * @package App\Model
 */
class LineItem
{
    /**
     * @var string
     */
    protected $sku;

    /**
     * @var float
     */
    protected $unitPrice;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $taxIdentifier;

    /**
     * @var float
     */
    protected $taxRate = 0;

    /**
     * @var float
     */
    protected $discount = 0;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return LineItem
     */
    public function setSku(string $sku): LineItem
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     * @return LineItem
     */
    public function setUnitPrice(float $unitPrice): LineItem
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return LineItem
     */
    public function setQuantity(int $quantity): LineItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxIdentifier(): string
    {
        return $this->taxIdentifier;
    }

    /**
     * @param string $taxIdentifier
     * @return LineItem
     */
    public function setTaxIdentifier(string $taxIdentifier): LineItem
    {
        $this->taxIdentifier = $taxIdentifier;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /**
     * @param float $taxRate
     * @return LineItem
     */
    public function setTaxRate(float $taxRate): LineItem
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return LineItem
     */
    public function setDiscount(float $discount): LineItem
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxAmount(): float
    {
        return $this->getTotalExcTax() * ($this->taxRate / 100);
    }

    /**
     * @return float
     */
    public function getTotalExcTax(): float
    {
        return ($this->unitPrice * $this->quantity) - $this->discount;
    }

    /**
     * @return float
     */
    public function getTotalIncTax(): float
    {
        return $this->getTotalExcTax() + $this->getTaxAmount();
    }
}
