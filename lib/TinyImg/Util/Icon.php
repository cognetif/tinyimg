<?php
namespace Cognetif\TinyImg\Util;

class Icon
{

    private $data = null;

    /**
     * Icon constructor.
     * @param $iconData
     */
    public function __construct($iconData)
    {
        $this->data = $iconData;
    }

    /**
     * @param string $icon
     * @param array $options
     * @return string
     */
    public function get(string $icon, array $options = []): string
    {
        if (array_key_exists($icon, $this->data)) {
            return self::renderIcon($this->data[$icon], $options);
        }

        return '';
    }


    /**
     * @param string $iconBase64
     * @param array $options
     * @return string
     */
    private function renderIcon($iconBase64, $options)
    {
        return sprintf('<img src="%s" %s%s%s%s%s />',
            $iconBase64,
            $this->getOptionAttribute('alt', $options, true),
            $this->getOptionAttribute('class', $options),
            $this->getOptionAttribute('style', $options),
            $this->getOptionAttribute('width', $options),
            $this->getOptionAttribute('height', $options)
        );

    }

    /**
     * @param string $name
     * @param array $options
     * @param bool $allowEmpty
     * @return string
     */
    private function getOptionAttribute($name, $options, $allowEmpty = false)
    {

        $attrName = htmlspecialchars($name);

        if (array_key_exists($name, $options)) {
            $attrValue = htmlspecialchars($options[$name]);
        } elseif ($allowEmpty) {
            $attrValue = "";
        } else {
            return "";
        }

        return sprintf(' %s="%s"', $attrName, $attrValue);

    }
}