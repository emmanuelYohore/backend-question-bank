<?php

class FormatException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notFormatMessage() {
        echo "Format not found\n";
    }

    public function notFormatIdMessage() {
        echo "Format id not found\n";
    }

    public function notCreateFormatMessage() {
        echo "Error create format\n";
    }

    public function notUpdateFormatMessage() {
        echo "Error update format\n";
    }

    public function notDeleteFormatMessage() {
        echo "Error delete format\n";
    }

}