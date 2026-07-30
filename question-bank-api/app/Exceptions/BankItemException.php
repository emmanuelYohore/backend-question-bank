<?php

class BankItemException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notBankItemsMessage() {
        echo "Bank items not found\n";
    }

    public function notBankItemIdMessage() {
        echo "Bank item id not found\n";
    }

    public function notCreateBankItemMessage() {
        echo "Error create bank item\n";
    }

    public function notUpdateBankItemMessage() {
        echo "Error update bank item\n";
    }

    public function notDeleteBankItemMessage() {
        echo "Error delete bank item\n";
    }

}