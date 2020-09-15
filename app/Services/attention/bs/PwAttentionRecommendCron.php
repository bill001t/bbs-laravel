<?php

namespace App\Services\attention\bs;

use App\Core\Tool;
use App\Services\attention\ds\dao\PwAttentionRecommendCronDao;

class PwAttentionRecommendCron
{

    /**
     * 获取所有任务
     *
     * @param int $uid
     * @return bool
     */
    public function getCron($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return array();
        return $this->_getDao()->get($uid);
    }

    /**
     * 获取所有任务
     *
     * @param int $uid
     * @return bool
     */
    public function getAllCron()
    {
        return $this->_getDao()->getAll();
    }

    /**
     * 增加单个任务
     *
     * @param int $uid
     * @return bool
     */
    public function replaceCron($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return false;
        return $this->_getDao()->replace(array('uid' => $uid, 'created_time' => Tool::getTime()));
    }

    /**
     * 删除单个任务
     *
     * @param int $uid
     * @return bool
     */
    public function deleteCron($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return false;
        return $this->_getDao()->_delete($uid);
    }

    /**
     * 根据时间删除
     *
     * @param int $created_time
     * @return bool
     */
    public function deleteByCreatedTime($created_time)
    {
        $created_time = intval($created_time);
        if ($created_time < 1) return false;
        return $this->_getDao()->deleteByCreatedTime($created_time);
    }

    /**
     *
     * @return PwAttentionRecommendCronDao
     */
    private function _getDao()
    {
        return app(PwAttentionRecommendCronDao::class);
    }
}