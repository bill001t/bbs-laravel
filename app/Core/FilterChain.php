<?php

namespace App\Core;

class FilterChain
{
    protected $_interceptors = array('_Na' => null);
    protected $_callBack = null;
    protected $_callBackArgs = [];
    protected $_interceptorArgs = [];

     public function setCallBack($callBack = null)
    {
        $this->_callBack = $callBack;

    }

    public function setCallBackArgs($args = [])
    {
        $this->_callBackArgs = $args;

    }

    public function setInterceptorArgs($args = [])
    {
        $this->_interceptorArgs = array_merge($this->_interceptorArgs, $args);

    }

    public function handle()
    {
        $args = $this->_interceptorArgs;

        if (null !== ($handler = $this->getHandler())) {
            $result = call_user_func_array(array($handler, 'preHandle'), $args);

            if ($result !== null) {
                return $result;
            }

            $result = $this->handle();
        } else {
            $result = call_user_func_array(array($this, 'handleCallback'), $args);
        }

        call_user_func_array(array($handler, 'postHandle'), $args);

        return $result;
    }

    protected function handleCallback()
    {
        reset($this->_interceptors);

        if ($this->_callBack === null) return null;

        if (is_string($this->_callBack) && !function_exists($this->_callBack)) {
            throw new WindException('[filter.WindHandlerInterceptorChain.handle] ' . $this->_callBack,
                WindException::ERROR_FUNCTION_NOT_EXIST);
        }

        return call_user_func_array($this->_callBack, (array)$this->_callBackArgs);
    }

    protected function getHandler()
    {
        if (count($this->_interceptors) <= 1) {
            return null;
        }

        $handler = next($this->_interceptors);

        if ($handler === false) {
            reset($this->_interceptors);
            return null;
        }

        if (method_exists($handler, 'handle')) {
            return $handler;
        }

        return $this->getHandler();
    }


    public function addInterceptors($interceptors)
    {
        if (is_array($interceptors))
            $this->_interceptors = array_merge($this->_interceptors, $interceptors);
        else
            $this->_interceptors[] = $interceptors;
    }

    public function reset()
    {
        $this->_interceptors = array('_Na' => null);
        $this->_callBack = null;
        $this->_args = array();
        return true;
    }
}

?>