<?php

namespace App\AccountingPlatform\Library\Odoo;

use OdooClient\Client;

/**
 * Class OdooClient
 * @package App\AccountingPlatform\Library
 */
class OdooClient extends Client
{
    /**
     * Call Methods model(s)
     *
     * @param string $model Model
     * @param string $method Method
     * @param array  $ids   Array of model id's
     *
     * @return boolean True is successful
     */
    public function methods($model, $method, $data)
    {
        $response = $this->getClient('object')->execute_kw(
            $this->database,
            $this->uid(),
            $this->password,
            $model,
            $method ,
            [$data]
        );

        return $response;
    }
}