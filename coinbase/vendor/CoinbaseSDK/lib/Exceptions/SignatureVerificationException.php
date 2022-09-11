<?php
namespace CoinbaseSDK\Exceptions;

class SignatureVerificationException extends CoinbaseException
{
    public function __construct($signature, $payloa