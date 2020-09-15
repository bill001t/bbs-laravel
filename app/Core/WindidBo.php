<?php

namespace App\Core;

use App\Services\Api\config\bs\WindidConfig;

/**
 * @author Jianmin Chen <sky_hold@163.com>
 * @version $Id: windidBoot.php 24569 2013-02-01 02:23:37Z jieyin $
 * @package wekit
 */
class windidBo
{
    public $config;

    public function __construct()
    {
        $this->config = new PwConfigBo($re = self::class);
    }

    public function getConfigService()
    {
        return app(WindidConfig::class);
    }

    /**
     * 获取全局配置
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->getConfigCacheValue();
    }

    protected function getConfigCacheValue()
    {
        $vkeys = array('site', 'components', 'verify', 'attachment');
        $array = app(WindidConfig::class)->fetchConfig($vkeys);
        $config = array();
        foreach ($vkeys as $key => $value) {
            $config[$value] = array();
        }
        foreach ($array as $key => $value) {
            $config[$value['name']] = $value['vtype'] != 'string' ? unserialize($value['value']) : $value['value'];
        }
        return $config;
    }
}