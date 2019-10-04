<?php

namespace Cognetif\TinyImg\Util;

use \PerchAPI_Settings;
use \PerchApi;

class SettingHelper
{

    /** @var  PerchApi_Settings */
    private $settings;

    public function __construct(PerchApi $api) {
        $this->settings = $api->get('Settings');
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->settings->get($key)->val();
    }

}