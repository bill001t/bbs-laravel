<?php

namespace App\Core;

class CommonErrorException extends \Exception
{
    private $error = null;

    public function __construct($message, $code = 0)
    {
        $message = $this->buildMessage($message);
        parent::__construct($message, $code);
    }

    public function buildMessage($message)
    {
        $message = str_replace(array("<br />", "<br>", "\r\n"), '', $message);

        return $message ;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function addError($error, $key = '') {
        if ($key === '')
            $this->error[] = $error;
        else
            $this->error[$key] = $error;
    }
}