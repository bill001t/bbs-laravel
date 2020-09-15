<?php

namespace App\Core\config;

use Core;

class PwConfigSet
{

    protected $namespace = 'site';
    protected $config = array();

    /**
     * @param string $namespace
     */
    public function __construct($namespace = '')
    {
        $namespace && $this->namespace = $namespace;
    }

    /**
     * 设置一个配置选项
     *
     * @param string $name 配置项
     * @param mixed $value 配置值
     * @param string $descrip 描述
     * @return PwConfigSet
     */
    public function set($name, $value, $descrip = null)
    {
        $this->config[$name] = array('name' => $name, 'value' => $value, 'descript' => $descrip);
        return $this;
    }

    /**
     * 返回当前配置的值
     *
     * @param string $name
     */
    public function get($name)
    {
        return isset($this->config[$name]) ? $this->config[$name]['value'] : '';
    }

    /**
     * 将数据持久化到数据库
     */
    public function flush()
    {
        Core::C()->setConfigs($this->namespace, $this->config);
    }

    public function getAll()
    {
        return $this->config;
    }
}

?>