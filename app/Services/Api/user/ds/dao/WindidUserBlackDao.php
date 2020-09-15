<?php

namespace App\Services\Api\user\ds\dao;

use App\Services\Api\user\ds\relation\WindidUserBlack;

class WindidUserBlackDao extends WindidUserBlack
{

    /**
     * 获取单条
     *
     * @param int $uid
     * @return array
     */
    public function getBlacklist($uid)
    {
        return self::find($uid);
    }

    /**
     * 获取单条
     *
     * @param array $uids
     * @return array
     */
    public function fetchBlacklist($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    /**
     * 更新
     *
     * @param array $blacklist (serialized array)
     * @return bool
     */
    public function replaceBlacklist($data)
    {
        return self::firstOrCreate($data);
    }

    /**
     * 删除
     *
     * @param int $uid
     * @return bool
     */
    public function deleteBlacklist($uid)
    {
        return self::destroy($uid);
    }
}