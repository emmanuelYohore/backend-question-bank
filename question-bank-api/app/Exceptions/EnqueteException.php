<?php

class EnqueteException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notEnquetesMessage() {
        echo "Enquetes not found\n";
    }

    public function notEnqueteIdMessage() {
        echo "Enquete id not found\n";
    }

    public function notCreateEnqueteMessage() {
        echo "Error create enquete\n";
    }

    public function notUpdateEnqueteMessage() {
        echo "Error update enquete\n";
    }

    public function notDeleteEnqueteMessage() {
        echo "Error delete enquete\n";
    }

}