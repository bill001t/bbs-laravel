<?php

namespace App\Services\education\ds\dao;

use App\Services\education\ds\relation\UserEducation;

class PwEducationDao extends UserEducation
{
    /**
     * 添加教育经历
     *
     * @param array $data
     * @return boolean|int
     */
    public function add($data)
    {
        return self::create($data);
    }

    /**
     * 更新教育经历
     *
     * @param int $id 教育经历ID
     * @param int $uid 用户ID
     * @param array $data
     * @return boolean|int
     */
    public function _update($id, $uid, $data)
    {
        return self::where('id', $id)
            ->where('uid', $uid)
            ->update($data);
    }

    /**
     * 删除教育经历
     *
     * @param int $id 教育经历ID
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
     * 根据教育经历ID获取该教育经历详细信息
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
     * 根据用户ID删除用户教育经历
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
     * 根据用户ID获得该用户的教育经历列表
     *
     * @param int $uid 用户ID
     * @param int $limit 返回条数
     * @param int $start 开始位置
     * @return array
     */
    public function getByUid($uid, $limit, $start)
    {
        return self::where('uid', $uid)
            ->orderby('start_time', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据用户ID统计该用户的教育经历
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