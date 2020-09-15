<?php

namespace App\Services\credit\bm;

use App\Core\Hook\SimpleHook;

class PwCreditOperationConfig
{

    private $_data = array();
    private $_map = array();
    private $_config = '/Services/credit/bm/operationConfig/baseconfig.php';
    private static $_instance = null;

    private function __construct()
    {
        $config = include app()->path() . $this->_config;
        $config = SimpleHook::getInstance('PwCreditOperationConfig')->runWithFilters($config);
        foreach ($config as $key => $value) {
            if ($value[1] && $value[3]) $this->_map[$value[1]][] = $key;
        }
        $this->_data = $config;
    }

    public static function getInstance()
    {
        isset(self::$_instance) || self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * 获取描述
     *
     * @param string $operate
     * @param array $var 待解析字符变量
     * @return string
     */
    public function getDescrip($operate, $var = array())
    {
        if (!isset($this->_data[$operate]) || empty($this->_data[$operate][2])) {
            return $operate;
        }
        $descrip = $this->_data[$operate][2];
        $_search = array();
        foreach ($var as $k => $v) {
            $_search[] = '{$' . $k . '}';
        }
        $descrip = str_replace($_search, array_values($var), $descrip);
        return $descrip;
    }

    public function getName($operate)
    {
        if (!isset($this->_data[$operate]) || empty($this->_data[$operate][0])) {
            return $operate;
        }
        return $this->_data[$operate][0];
    }

    /**
     * judge if the operate is in the "global->credit->strategy"
     *
     * @author  xiaoxia.xuxx
     * @changeTime 2012-8-27
     * @param  string $operate [description]
     * @return boolean           [description]
     */
    public function isCreditPop($operate)
    {
        if (!isset($this->_data[$operate])) {
            return false;
        }
        return $this->_data[$operate][3];
    }

    /**
     * 获取所有操作
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * 获取分类
     *
     * @return array
     */
    public function getMap()
    {
        return $this->_map;
    }

    public function addConfig($config)
    {
        //$this->_config[] = $config;
    }
}