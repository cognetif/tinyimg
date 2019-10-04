<?php
namespace Cognetif\TinyImg\Traits;

use \PerchUtil;


trait PerchTrait {


    /**
     * @var PerchUtil
     */
    protected $perchUtil;


    protected function debugLog(string $message = ''):void {
        $this->debug($message, 'log');
    }

    protected function debugError(string $message = ''):void {
        $this->debug($message, 'error');
    }

    protected function debugTemplate(string $message = ''):void {
        $this->debug($message, 'template');
    }

    protected function debugDb(string $message = ''):void {
        $this->debug($message, 'db');
    }

    protected function debugRoute(string $message = ''):void {
        $this->debug($message, 'route');
    }


    private function debug(string $message, string $type):void {
        $this->perchUtil::debug($message, $type);
    }
}