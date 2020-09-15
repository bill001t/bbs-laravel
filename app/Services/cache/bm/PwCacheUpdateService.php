<?php

namespace App\Services\cache\bm;

use App\Services\usergroup\bm\PwUserGroupsService;
use Core;

/**
 * 全局缓存更新服务
 */
class PwCacheUpdateService
{

    /**
     * 更新所有缓存
     */
    public function updateAll()
    {
        $this->updateConfig();
        $this->updateGroup();
        $this->updateMedal();
    }

    /**
     * 更新全局配置 config
     */
    public function updateConfig()
    {
        Core::cache()->set('config', $this->getConfigCacheValue());
    }

    /**
     * 获取全局缓存数据
     *
     * @return array
     */
    public function getConfigCacheValue()
    {
        $vkeys = array('site', 'credit', 'bbs', 'attachment', 'components', 'seo', 'nav', 'windid');
        $array = Core::C()->fetchConfig($vkeys);
        $config = array();
        foreach ($vkeys as $key => $value) {
            $config[$value] = array();
        }
        foreach ($array as $key => $value) {
            $config[$value->namespace][$value->name] = $value->vtype != 'string' ? unserialize($value->value) : $value->value;
        }
        return $config;
    }

    /**
     * 更新用户组缓存
     */
    public function updateGroup()
    {
        $bm = app(PwUserGroupsService::class);
        $bm->updateLevelCache();
        $bm->updateGroupRightCache();
        $bm->updateGroupCache();
    }

    /**
     * 更新勋章缓存
     */
    /*public function updateMedal()
    {
        app(PwMedalService::class)->updateCache();
    }*/
}