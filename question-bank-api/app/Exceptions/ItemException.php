<?php

class ItemException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notItemsMessage() {
        echo "Items not found\n";
    }

    public function notItemIdMessage() {
        echo "Item id not found\n";
    }

    public function notCreateItemMessage() {
        echo "Error create item\n";
    }

    public function notUpdateItemMessage() {
        echo "Error update item\n";
    }

    public function notDeleteItemMessage() {
        echo "Error delete item\n";
    }

}