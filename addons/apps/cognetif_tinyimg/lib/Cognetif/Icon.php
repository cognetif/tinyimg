<?php

namespace Cognetif;

class Icon
{

    private static $data = null;

    /**
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function get($icon, $options = [])
    {
        self::load();
        if (array_key_exists($icon, self::$data)) {
            return self::renderIcon(self::$data[$icon], $options);
        }
        return '';
    }

    private static function load()
    {
        if (is_null(self::$data)) {
            self::$data = require(__DIR__ . '/../../util/icons.php');
        }
    }

    /**
     * @param string $iconBase64
     * @param array $options
     * @return string
     */
    private static function renderIcon($iconBase64, $options)
    {

        return sprintf('<img src="%s" %s%s%s%s%s />',
            $iconBase64,
            self::getOptionAttribute('alt', $options, true),
            self::getOptionAttribute('class', $options),
            self::getOptionAttribute('style', $options),
            self::getOptionAttribute('width', $options),
            self::getOptionAttribute('height', $options)
        );
    }

    /**
     * @param string $name
     * @param array $options
     * @param bool $allowEmpty
     * @return string
     */
    private static function getOptionAttribute($name, $options, $allowEmpty = false)
    {

        $api = new \PerchAPI(1.0, 'cognetif_tinyimg');
        /** @var \PerchAPI_HTML $html */
        $html = $api->get('HTML');

        $attrName = $html->encode($name);

        if (array_key_exists($name, $options)) {
            $attrValue = $html->encode($options[$name]);
        } elseif ($allowEmpty) {
            $attrValue = "";
        } else {
            return "";
        }

        return sprintf(' %s="%s"', $attrName, $attrValue);

    }
}