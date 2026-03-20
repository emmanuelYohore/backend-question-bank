<?php

class UserException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notUsersMessage() {
        echo "Users not found\n";
    }

    public function notUserIdMessage() {
        echo "User id not found\n";
    }

    public function notCreateUserMessage() {
        echo "Error create user\n";
    }

    public function notUpdateUserMessage() {
        echo "Error update user\n";
    }

    public function notDeleteUserMessage() {
        echo "Error delete user\n";
    }

}


?>