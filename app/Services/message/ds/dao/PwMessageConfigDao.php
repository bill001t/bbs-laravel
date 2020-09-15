<?php

namespace App\Services\message\ds\dao;

use App\Services\message\ds\relation\MessageConfig;

class PwMessageConfigDao extends MessageConfig
{
    /**
     * 获取用户消息配置
     *
     * @param int $uid
     * @return bool
     */
    public function getMessageConfig($uid)
    {
        return self::find($uid);
    }

    /**
     * 获取用户消息配置
     *
     * @param array $uids
     * @return array
     */
    public function fetchMessageConfig($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    /**
     * 用户配置
     *
     * @param array $data
     * @return int
     */
    public function setMessageConfig($data)
    {
        $data = array(
            'uid' => $data['uid'],
            'privacy' => $data['privacy'],
            'notice_types' => $data['notice_types']
        );

        return self::firstOrCreate($data);
    }

}