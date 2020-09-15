<?php

namespace App\Services\message\bs;

use App\Services\message\ds\dao\PwMessageConfigDao;

class PwMessageMessages
{

    /**
     * 获取用户消息配置
     *
     * @param int $uid
     * @return array
     */
    public function getMessageConfig($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return array();
        return $this->_getMessageConfigDao()->getMessageConfig($uid);
    }

    /**
     * 批量获取用户消息配置
     *
     * @param array $uids
     * @return array
     */
    public function fetchMessageConfig($uids)
    {
        if (!is_array($uids) || !count($uids)) {
            return array();
        }
        return $this->_getMessageConfigDao()->fetchMessageConfig($uids);
    }

    /**
     * 用户配置
     *
     * @param array $data
     * @return int
     */
    public function setMessageConfig($uid, $privacy, $notice_types)
    {
        $uid = intval($uid);
        if ($uid < 1) return array();
        $data = array(
            'uid' => $uid,
            'privacy' => $privacy,
            'notice_types' => $notice_types
        );
        return $this->_getMessageConfigDao()->setMessageConfig($data);
    }


    /**
     *
     * Enter description here ...
     * @return PwMessageConfigDao
     */
    private function _getMessageConfigDao()
    {
        return app(PwMessageConfigDao::class);
    }
}