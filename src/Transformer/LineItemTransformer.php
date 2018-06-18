<?php

namespace App\Transformer;

use App\Model\LineItem;

/**
 * Class LineItemTransformer
 * @package App\Transformer
 */
class LineItemTransformer
{
    /**
     * @param array $data
     * @return LineItem
     */
    public function transform(array $data): LineItem
    {
        $lineItem = new LineItem();
        $lineItem->setSku($data['sku'])
            ->setUnitPrice($data['unitPrice'])
            ->setQuantity($data['quantity'])
            ->setTaxRate($data['taxRate'])
            ->setTaxIdentifier($data['taxIdentifier'])
            ->setDiscount($data['discount']);

        return $lineItem;
    }
}
