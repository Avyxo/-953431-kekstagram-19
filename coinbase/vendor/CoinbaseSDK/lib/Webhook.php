<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Exceptions\InvalidResponseException;
use CoinbaseSDK\Exceptions\SignatureVerificationException;
use CoinbaseSDK\Resources\Event;
use CoinbaseSDK\Util;

class Webhook
{
    public static function buildEvent($payload, $sigHeader, $secret)
    {
        $data = null;

        $data = \json_decode($payload, true);

        if (json_last_error()) {
            throw new InvalidResponseExcepti