<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Exceptions\InvalidResponseException;
use CoinbaseSDK\Exceptions\SignatureVerificationException;
use CoinbaseSDK\Resources\Event;
use CoinbaseSDK\Util;

class Webhook
{
    publi