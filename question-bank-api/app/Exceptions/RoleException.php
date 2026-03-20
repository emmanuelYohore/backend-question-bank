<?php

class RoleException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function notRolesMessage() {
        echo "Roles not found\n";
    }

    public function notRoleIdMessage() {
        echo "Role id not found\n";
    }

    public function notCreateRoleMessage() {
        echo "Error create role\n";
    }

    public function notUpdateRoleMessage() {
        echo "Error update role\n";
    }

    public function notDeleteRoleMessage() {
        echo "Error delete role\n";
    }

}