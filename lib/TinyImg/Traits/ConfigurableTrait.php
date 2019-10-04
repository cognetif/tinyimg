<?php
namespace Cognetif\TinyImg\Traits;

use Cognetif\TinyImg\Util\SettingHelper;
use Cognetif\TinyImg\Exceptions\InvalidValueException;

trait ConfigurableTrait
{

    /**
     * @var SettingHelper
     */
    private $settings;

    protected function config($key)
    {
        return $this->settings->get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    private function getBoolConfig(string $key): bool
    {
        if ($val = filter_var($this->config($key), FILTER_VALIDATE_BOOLEAN)) {
            return $val;
        }
        return false;
    }

    /**
     * @param string $key
     * @return int
     * @throws InvalidValueException
     */
    private function getIntConfig(string $key): int
    {
        if ($val = filter_var($this->config($key), FILTER_VALIDATE_INT)) {
            return $val;
        }

        throw new InvalidValueException();
    }


    /**
     * @return int
     */
    protected function configBatchSize(): int
    {
        try {
            return $this->getIntConfig('cognetif_tinyimg_batch_size');
        } catch (InvalidValueException $e) {
            return -1;
        }
    }

    /**
     * @return bool
     */
    protected function configIsDevMode(): bool
    {
        return $this->getBoolConfig('cognetif_tinyimg_dev_mode');
    }

    /**
     * @return bool
     */
    protected function configIsProdMode(): bool
    {
        return !$this->configIsDevMode();
    }

    /**
     * @return bool
     */
    protected function configCompressOriginal(): bool
    {
        return $this->getBoolConfig('cognetif_tinyimg_compress_original');
    }
}