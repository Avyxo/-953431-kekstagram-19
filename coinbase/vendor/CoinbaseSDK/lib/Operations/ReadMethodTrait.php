<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;
use CoinbaseSDK\ApiResourceList;

trait ReadMethodTrait
{
    public static function retrieve($id, $headers = [])
    {
        if (!\is_scalar($id)) {
            throw new \Exception('Invalid id provided.');
        }

        $client = static::getClient();
        $path = Util::joinPat