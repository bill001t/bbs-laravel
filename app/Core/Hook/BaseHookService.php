<?php

namespace App\Core\Hook;

abstract class BaseHookService
{
    protected $_do = array();
    protected $_srv;
    protected $_key = array();
    protected $_ready = false;

    public function __construct($hookKey = '')
    {
        !$hookKey && $hookKey = get_class($this);
        $this->setHook($hookKey);
    }

    public function setSrv($srv)
    {
        $this->_srv = $srv;
    }

    public function setHook($hookKey, $pre = 'm')
    {
        $this->_key[] = $pre . '_' . $hookKey;
    }

    protected function _prepare()
    {
        if ($this->_ready) {
            return !empty($this->_do);
        }
        !$this->_srv && $this->_srv = $this;
        foreach ($this->_key as $key => $hookKey) {
            if (!$hooks = Hook::getRegistry($hookKey)) continue;
            if (!$map = Hook::resolveActionHook($hooks, $this->_srv)) continue;
            foreach ($map as $key => $value) {
                $this->appendDo(app($value['class']), array($this->_srv));
            }
        }
        $this->_ready = true;
        return !empty($this->_do);
    }

    abstract protected function _getInterfaceName();

    public function appendDo($do)
    {
        $instanceN = $this->_getInterfaceName();
        if ($do instanceof $instanceN) {
            $this->_do[] = $do;
        }
    }

    public function runDo($method)
    {
        if (!$this->_prepare()) return;
        $args = array_slice(func_get_args(), 1);
        foreach ($this->_do as $key => $_do) {
            call_user_func_array(array($_do, $method), $args);
        }
    }

    public function runWithVerified($method)
    {
        if (!$this->_prepare()) return true;
        $args = array_slice(func_get_args(), 1);
        foreach ($this->_do as $key => $_do) {
            if (($result = call_user_func_array(array($_do, $method), $args)) !== true) return $result;
        }
        return true;
    }

    public function runWithFilters($method, $value)
    {
        if (!$this->_prepare()) return $value;
        $args = array_slice(func_get_args(), 1);
        foreach ($this->_do as $key => $_do) {
            $args[0] = $value;
            $value = call_user_func_array(array($_do, $method), $args);
        }
        return $value;
    }

    public function getAttribute($var)
    {
        if (!property_exists($this, $var)) return false;
        $result = $this->$var;
        if (func_num_args() > 1) {
            $args = array_slice(func_get_args(), 1);
            $result = $this->_getAttribute($result, $args);
        }
        return $result;
    }

    public function getHookKey()
    {
        return $this->_key[0];
    }

    private function _getAttribute($result, $attributes)
    {
        foreach ($attributes as $value) {
            if (is_array($result)) {
                $result = $result[$value];
            } elseif (is_object($result) && property_exists($result, $value)) {
                $result = $result->$value;
            } else {
                return false;
            }
        }
        return $result;
    }
}