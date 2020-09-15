<?php

namespace App\Services\online\bs;

use App\Core\ErrorBag;
use App\Services\online\dm\PwOnlineDm;
use App\Services\online\ds\dao\PwGuestOnlineDao;

class PwGuestOnline
{

    /**
     * 获取一条游客信息
     *
     * @param int $ip
     * @param int $created_time
     * @return array
     */
    public function getInfo($ip, $created_time)
    {
        $ip = (int)$ip;
        $created_time = (int)$created_time;
        if (empty($ip) || empty($created_time)) return false;
        return $this->_getGuestOnlineDao()->getInfo($ip, $created_time);
    }

    /**
     * 统计在线游客数
     *
     * @param int $fid
     * @param int $tid
     * @return int
     */
    public function getOnlineCount($fid = 0, $tid = 0)
    {
        $fid = (int)$fid;
        $tid = (int)$tid;
        return $this->_getGuestOnlineDao()->getOnlineCount($fid, $tid);
    }

    /**
     * 添加一条游客信息
     *
     * @param PwOnlineDm $dm
     * @return bool
     */
    public function replaceInfo(PwOnlineDm $dm)
    {
        $resource = $dm->beforeAdd();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getGuestOnlineDao()->replaceInfo($dm->getData());
    }

    /**
     * 删除一条游客信息
     *
     * @param int $ip
     * @param int $createdTime
     * @return bool
     */
    public function deleteInfo($ip, $createdTime)
    {
        $ip = (int)$ip;
        $created_time = (int)$createdTime;
        if (empty($ip) || empty($createdTime)) return false;
        return $this->_getGuestOnlineDao()->deleteInfo($ip, $createdTime);
    }

    /**
     * 删除过期的游客信息
     *
     * @param int $modifyTime
     * @return int
     */
    public function deleteInfoByTime($modifyTime)
    {
        $modifyTime = (int)$modifyTime;
        if ($modifyTime < 0) return false;
        return $this->_getGuestOnlineDao()->deleteByLastTime($modifyTime);
    }

    private function _getGuestOnlineDao()
    {
        return app(PwGuestOnlineDao::class);
    }
}

?>