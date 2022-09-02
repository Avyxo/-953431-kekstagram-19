<?php
namespace CoinbaseSDK\Exceptions;

class InvalidResponseException extends CoinbaseException
{
    public function __construct($message = '', $body = '')
    {