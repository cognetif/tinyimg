<?php
namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Traits\ConfigurableTrait;
use Cognetif\TinyImg\Util\SettingHelper;
use Tinify\Exception as TinyException;
use Tinify\Source;
use Tinify\Tinify;

class TinyApi {

    use ConfigurableTrait;

    public function __construct(SettingHelper $settings) {
        $this->settings = $settings;
    }
    /**
     * @param $path
     * @return bool|int
     * @throws TinyException  //Yes it is thrown
     */
    public function tinifyImage($path)
    {
        $key = $this->config('cognetif_tinyimg_api_key');
        Tinify::setKey($key);
        return Source::fromFile($path)->toFile($path);
    }
}