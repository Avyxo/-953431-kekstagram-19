<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;
use CoinbaseSDK\ApiResourceList;

trait ReadMethodTrait
{
    public static function retrieve($id, $headers = [])
    {
        if (!\is_sca