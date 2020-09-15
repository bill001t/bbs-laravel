<?php

namespace App\Services\Api;

use App\Services\Api\base\WindidUtility;
use App\Services\Api\config\bs\WindidConfig;
use App\Services\Api\notify\bm\NotifyService;
use App\Services\config\bm\WindidCreditSetService;

class WindidConfigApi
{
    public function get($name)
    {
        $key = '';
        if (strpos($name, ':') !== false) {
            list($namespace, $key) = explode(':', $name);
        } else {
            $namespace = $name;
        }
        $config = $this->_getConfigDs()->getValues($namespace);
        return $key ? $config[$key] : $config;
    }

    public function getConfig($namespace)
    {
        return $this->_getConfigDs()->getConfig($namespace);
    }

    public function fetchConfig($namespace)
    {
        return $this->_getConfigDs()->fetchConfig($namespace);
    }

    public function getConfigByName($namespace, $name)
    {
        return $this->_getConfigDs()->getConfigByName($namespace, $name);
    }

    public function getValues($namespace)
    {
        return $this->_getConfigDs()->getValues($namespace);
    }

    /**
     * 设置配置
     *
     * @param string $namespace 命名空间
     * @param array $keys
     */
    public function setConfig($namespace, $key, $value)
    {
        $this->_getConfigDs()->setConfig($namespace, $key, $value);
        return WindidUtility::result(true);
    }

    public function setConfigs($namespace, $data)
    {
        $this->_getConfigDs()->setConfigs($namespace, $data);
        return WindidUtility::result(true);
    }

    public function deleteConfig($namespace)
    {
        $this->_getConfigDs()->deleteConfig($namespace);
        return WindidUtility::result(true);
    }

    public function deleteConfigByName($namespace, $name)
    {
        $this->_getConfigDs()->deleteConfigByName($namespace, $name);
        return WindidUtility::result(true);
    }

    public function setCredits($credits)
    {
        $this->_getConfigService()->setLocalCredits($credits);
        $this->_getNotifyService()->send('setCredits', array(), WINDID_CLIENT_ID);
        return WindidUtility::result(true);
    }

    private function _getConfigDs()
    {
        return app(WindidConfig::class);
    }

    private function _getConfigService()
    {
        return app(WindidCreditSetService::class);
    }

    private function _getNotifyService()
    {
        return app(NotifyService::class);
    }
}

?>