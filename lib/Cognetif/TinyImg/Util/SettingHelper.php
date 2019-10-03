<?php

namespace Cognetif\TinyImg\Util;

class SettingHelper
{

    private static function get($key)
    {
        $api = new \PerchAPI(1.0, 'cognetif_tinyimg');
        $settings = $api->get('Settings');
        return $settings->get($key)->val();
    }


    /**
     * @return mixed
     */
    public static function getBatchSize()
    {
        return filter_var(self::get('cognetif_tinyimg_batch_size'), FILTER_SANITIZE_NUMBER_INT);
    }

    public static function isDevMode()
    {
        return filter_var(self::get('cognetif_tinyimg_dev_mode'), FILTER_VALIDATE_BOOLEAN);
    }

    public static function isProdMode()
    {
        return !filter_var(self::get('cognetif_tinyimg_dev_mode'), FILTER_VALIDATE_BOOLEAN);
    }
}