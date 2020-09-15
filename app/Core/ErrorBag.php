<?php

namespace App\Core;

class ErrorBag
{
    protected $error = array();

    public function __construct($error = '', $var = array(), $data = array())
    {
        $this->addError($error, $var, $data);
    }

    public function addError($error, $var = array(), $data = array())
    {
        if (!$error) return false;
        $tmp = new \stdClass();
        $tmp->msg = $var ? array($error, $var) : $error;
        $tmp->data = $data;
        $this->error[] = $tmp;
        return true;
    }

    public function getError($isAll = false)
    {
        if ($isAll !== false) {
            return $this->error;
        } else {
            $tmp = end($this->error);
            return $tmp ? $tmp->msg : '';
        }
    }

    public function getData()
    {
        $tmp = end($this->error);
        return $tmp ? $tmp->data : '';
    }
}

