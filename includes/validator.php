<?php

class validator {

    private $errors = [];

    public function notEmpty($fields, $value, $message) {
        if(empty($value)) {
            $this->errors[$fields] = $message;
        }
    }

    public function validEmail($fields, $value, $message) {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fields] = $message;
        }
    }

    public function isValid() {
        if(empty($this->errors)) return true;
        else return false;
    }

    public function getErrors() {
        return $this->errors;
    }

}