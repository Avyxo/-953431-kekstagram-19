<?php
namespace CoinbaseSDK\Operations;

trait SaveMethodTrait
{
    public function save($headers = [])
    {
        $id = $this->getPrimaryKeyValue();

        