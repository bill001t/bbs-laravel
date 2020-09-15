<?php

namespace App\Services\Api\config\ds\dao;

use App\Services\Api\config\ds\relation\WindidConfig;

class WindidConfigDao extends WindidConfig
{
    /**
     * 根据空间名字获得该配置信息
     *
     * @param stirng $namespace 空间名字
     * @return array
     */
    public function getConfigs($namespace)
    {
        return self::where('namespace', $namespace)
            ->get();
    }

    /**
     * 根据空间名字获得该配置信息
     *
     * @param array $namespace 空间名字序列
     * @return array
     */
    public function fetchConfigs($namespace)
    {
        return self::whereIn('namespace', $namespace)
            ->get();
    }

    /**
     * 获取某个配置
     *
     * @param string $namespace
     * @param string $name
     * @return array
     */
    public function getConfigByName($namespace, $name)
    {
        return self::where('namespace', $namespace)
            ->where('name', $name)
            ->first();
    }

    /**
     * 批量设置配置项
     *
     * @param array $data 待设置的配置项
     * @return boolean
     */
    public function storeConfigs($data)
    {
        foreach ($data as $value) {
            $this->storeConfig($value['namespace'], $value['name'], $value['value']);
        }
        return true;
    }

    /**
     * 存储配置项
     *
     * @param string $namespace 配置项命名空间
     * @param string $name 配置项名
     * @param mixed $value 配置项的值
     * @param string $descrip 配置项描述
     * @return boolean
     */
    public function storeConfig($namespace, $name, $value, $descrip = null)
    {
        $array = array();
        list($array['vtype'], $array['value']) = $this->_toString($value);
        isset($descrip) && $array['description'] = $descrip;
        if ($this->getConfigByName($namespace, $name)) {
            $result = self::where('namespace', $namespace)
                ->where('name', $name)
                ->update($array);
        } else {
            $array['name'] = $name;
            $array['namespace'] = $namespace;
            $result = self::create($array);
        }
        SimpleHook::getInstance('PwConfigDao_update')->runDo($namespace);
        return $result;
    }

    /**
     * 删除配置项
     *
     * @param string $namespace 配置项所属空间
     * @return boolean
     */
    public function deleteConfig($namespace)
    {
        self::where('namespace', $namespace)
            ->delete();
        SimpleHook::getInstance('PwConfigDao_update')->runDo($namespace);

        return true;
    }

    /**
     * 删除配置项
     *
     * @param string $namespace 配置项所属空间
     * @param string $name 配置项名字
     * @return boolean
     */
    public function deleteConfigByName($namespace, $name)
    {
        self::where('namespace', $namespace)
            ->where('name', $name)
            ->delete();
        SimpleHook::getInstance('PwConfigDao_update')->runDo($namespace);

        return true;
    }

    /**
     * 将数据转换为字符串
     *
     * @param mixed $value 待处理的数据
     * @return array 返回处理后的数据，第一个代表该数据的类型，第二个代表该数据处理后的数据串
     */
    private function _toString($value)
    {
        $vtype = 'string';
        if (is_array($value)) {
            $value = serialize($value);
            $vtype = 'array';
        } elseif (is_object($value)) {
            $value = serialize($value);
            $vtype = 'object';
        }
        return array($vtype, $value);
    }
}
