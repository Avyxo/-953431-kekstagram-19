<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;

trait UpdateMethodTrait
{
    public function update($headers = [])
    {
        $id = $this->getPrimaryKeyValue();

        if (!\is_scalar($id) || null