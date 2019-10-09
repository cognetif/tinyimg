<?php
namespace Cognetif\TinyImg\Exceptions;
use \Exception;

class DivisionByZeroException extends Exception {
    public function __construct($id) {
        parent::__construct('Cognetif TinyImg original file size is 0 for id' . $id);
    }
}