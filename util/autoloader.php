<?php
/**
 * Add Composer autoloader for vendor dependencies
 */
require_once(__DIR__ . "/../vendor/autoload.php");
if (!defined('COGNETIF_SITE_PATH')) {
    define('COGNETIF_SITE_PATH', __DIR__ . '/../../../../../');
}