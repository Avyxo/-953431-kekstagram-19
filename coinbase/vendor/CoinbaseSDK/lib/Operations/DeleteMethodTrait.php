<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;

trait DeleteMethodTrait
{
    public function delete($headers = [])
    {
        $id = $this->getPrimaryKeyValue();

        if (!\is_scalar($id)) {
            throw new