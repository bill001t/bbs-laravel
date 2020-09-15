<?php

namespace App\Services\Api\user\bs;

use App\Services\Api\user\ds\dao\WindidUserBlackDao;

class WindidUserBlack
{

    /**
     * 获取用户黑名单
     *
     * @param int $uid
     * @return array
     */
    public function getBlacklist($uid)
    {
        $blacklist = array();
        $uid = intval($uid);
        if ($uid < 1) return $blacklist;
        $rs = $this->_getBlacklistDao()->getBlacklist($uid);
        if ($rs['blacklist']) {
            $blacklist = @unserialize($rs['blacklist']);
        }
        return $blacklist;
    }

    /**
     * 批量获取用户黑名单
     *
     * @param array $uids
     * @return array
     */
    public function fetchBlacklist($uids)
    {
        if (!is_array($uids) || !count($uids)) {
            return array();
        }
        return $this->_getBlacklistDao()->fetchBlacklist($uids);
    }

    /**
     *
     * 添加用户黑名单
     * @param int $uid
     * @param int $blackUid
     */
    public function addBlackUser($uid, $blackUid)
    {
        $uid = intval($uid);
        $blackUid = intval($blackUid);
        if ($uid < 1 || $blackUid < 1) return false;
        $blackList = $this->getBlacklist($uid);
        if (in_array($blackUid, $blackList)) {
            return true;
        }
        $blackList[] = $blackUid;
        return $this->setBlacklist($uid, $blackList);
    }

    public function setBlacklist($uid, $blackList)
    {
        if (!is_array($blackList)) return false;
        $data['uid'] = $uid;
        $data['blacklist'] = serialize($blackList);
        return $this->_getBlacklistDao()->replaceBlacklist($data);
    }


    /**
     * 删除
     *
     * @param int $uid
     * @return bool
     */
    public function deleteBlacklist($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return false;
        return $this->_getBlacklistDao()->deleteBlacklist($uid);
    }

    public function deleteBlackUser($uid, $blackUid)
    {
        $uid = intval($uid);
        $blackUid = intval($blackUid);
        if ($uid < 1 || $blackUid < 1) return false;
        $blackList = $this->getBlacklist($uid);
        $key = array_search($blackUid, $blackList);
        if ($key === false) {
            return false;
        }
        unset($blackList[$key]);
        return $this->setBlacklist($uid, $blackList);
    }

    /**
     * @return WindidUserBlackDao
     */
    protected function _getBlacklistDao()
    {
        return app(WindidUserBlackDao::class);
    }
}