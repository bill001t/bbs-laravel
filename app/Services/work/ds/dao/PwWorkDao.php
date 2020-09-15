<?php

namespace App\Services\work\ds\dao;

use App\Services\work\ds\relation\PwWork;
use DB;

class PwWorkDao extends PwWork
{
    /**
     * 添加工作经历
     *
     * @param array $data
     * @return boolean|int
     */
    public function add($data)
    {
        return self::create($data);
    }

    /**
     * 更新工作经历
     *
     * @param int $id 工作经历ID
     * @param int $uid 用户ID
     * @param array $data
     * @return boolean|int
     */
    public function _update($id, $uid, $data)
    {
        unset($data['uid']);

        return self::where('id', $id)
            ->where('uid', $uid)
            ->update($data);
    }

    /**
     * 删除工作经历
     *
     * @param int $id 工作经历ID
     * @param int $uid 对应用户ID
     * @return boolean|int
     */
    public function _delete($id, $uid)
    {
        return self::where('id', $id)
            ->where('uid', $uid)
            ->delete();
    }

    /**
     * 根据工作经历ID获取该工作经历详细信息
     *
     * @param int $id 经历ID
     * @param int $uid 用户ID
     * @return array
     */
    public function get($id, $uid)
    {
        return self::where('id', $id)
            ->where('uid', $uid)
            ->first();
    }

    /**
     * 根据用户ID删除用户工作经历
     *
     * @param int $uid
     * @return boolean|int
     */
    public function deleteByUid($uid)
    {
        return self::where('uid', $uid)
            ->delete();
    }

    /**
     * 根据用户ID获得该用户的工作经历列表
     *
     * @param int $uid 用户ID
     * @param int $limit 返回条数
     * @param int $start 开始位置
     * @return array
     */
    public function getByUid($uid, $limit, $start)
    {
        return self::where('uid', $uid)
            ->orderby('starty', 'desc')
            ->orderby('startm', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据用户ID统计该用户的工作经历
     *
     * @param int $uid
     * @return int
     */
    public function countByUid($uid)
    {
        return self::where('uid', $uid)
            ->count();
    }
}