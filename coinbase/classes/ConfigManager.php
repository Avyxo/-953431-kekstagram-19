<?php

if (!defined('_PS_VERSION_')) {
    exit();
}

/**
 * For testing purposes we wrap the Configuration in a wrapper class
 * so that we can easily mock it.
 */
class ConfigManager
{
    public function addFields()
    {
        $or