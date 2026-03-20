<?php

class RepondantException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notRepondantsMessage() {
        echo "Repondants not found\n";
    }

    public function notRepondantIdMessage() {
        echo "Repondant id not found\n";
    }

    public function notCreateRepondantMessage() {
        echo "Error create repondant\n";
    }

    public function notUpdateRepondantMessage() {
        echo "Error update repondant\n";
    }

    public function notDeleteRepondantMessage() {
        echo "Error delete repondant\n";
    }

}