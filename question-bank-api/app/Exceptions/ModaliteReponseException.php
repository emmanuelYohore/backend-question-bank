<?php

class ModaliteReponseException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notModaliteReponsesMessage() {
        echo "Modalite Reponses not found\n";
    }

    public function notModaliteReponseIdMessage() {
        echo "Modalite Reponse id not found\n";
    }

    public function notCreateModaliteReponseMessage() {
        echo "Error create modalite Reponse\n";
    }

    public function notUpdateModaliteReponseMessage() {
        echo "Error update modalite Reponse\n";
    }

    public function notDeleteModaliteReponseMessage() {
        echo "Error delete modalite Reponse\n";
    }

}