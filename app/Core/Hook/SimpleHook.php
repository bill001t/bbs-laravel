<?php

namespace App\Core\Hook;

class SimpleHook
{
    private static $_instance = array();
    protected $_do = array();

    private function __construct($hookKey)
    {
        if (!$hooks = Hook::getRegistry('s_' . $hookKey)) return;
        if (!$map = Hook::resolveActionHook($hooks)) return;
        foreach ($map as $key => $value) {
            $this->appendDo(app($value['class']), $value['method']);
        }
    }

    public static function getInstance($hookKey)
    {
        if (!isset(self::$_instance[$hookKey])) {
            self::$_instance[$hookKey] = new self($hookKey);
        }
        return self::$_instance[$hookKey];
    }

    public function appendDo($do, $method)
    {
        if ($method && method_exists($do, $method)) {
            $this->_do[] = array($do, $method);
        }
    }

    public function runDo()
    {
        if (!$this->_do) return;
        $args = func_get_args();
        foreach ($this->_do as $key => $_do) {
            call_user_func_array($_do, $args);
        }
    }

    public function runWithVerified()
    {
        if (!$this->_do) return true;
        $args = func_get_args();
        foreach ($this->_do as $key => $_do) {
            if (($result = call_user_func_array($_do, $args)) !== true) return $result;
        }
        return true;
    }

    public function runWithFilters($value)
    {
        if (!$this->_do) return $value;
        $args = func_get_args();
        foreach ($this->_do as $key => $_do) {
            $args[0] = $value;
            $value = call_user_func_array($_do, $args);
        }
        return $value;
    }
}