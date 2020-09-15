<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagAttention;

class PwTagAttentionDao extends PwTagAttention
{
    /**
     * 根据uid和tagId获取话题
     *
     * @param int $uid
     * @param int $tagId
     * @return array
     */
    public function get($uid, $tagId)
    {
        return self::where('tag_id', $tagId)
            ->where('uid', $uid)
            ->get();
    }

    /**
     * 统计我关注的话题
     *
     * @param int $uid
     * @return int
     */
    public function countByUid($uid)
    {
        return self::where('uid', $uid)
            ->count();
    }

    /**
     * 取我关注的热门话题
     *
     * @param int $uid
     * @param array $tagIds
     * @return int
     */
    public function getAttentionByUidAndTagsIds($uid, $tagIds)
    {
        return self::whereIn('tag_id', $tagIds)
            ->where('uid', $uid)
            ->get();
    }

    /**
     * 获取我关注的话题
     *
     * @param int $uid
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getByUid($uid, $limit = 100, $start = 0)
    {
        return self::where('uid', $uid)
            ->orderby('last_read_time', 'desc')
            ->paginate($limit);
    }

    /**
     * 统计关注话题的用户
     *
     * @param int $tagId
     * @return array
     */
    public function countByTagId($tagId)
    {
        return self::where('tag_id', $tagId)
            ->count();
    }

    /**
     * 获取关注话题的用户
     *
     * @param int $tagId
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getByTagId($tagId, $start, $limit)
    {
        return self::where('tag_id', $tagId)
            ->paginate($limit);
    }

    /**
     * 添加一条关注
     *
     * @param array $data
     * @return int
     */
    public function add($data)
    {
        return self::firstOrCreate($data);
    }

    /**
     * 删除一条关注
     *
     * @param int $uid
     * @param int $tagId
     * @return int
     */
    public function _delete($uid, $tagId)
    {
        return self::where('tag_id', $tagId)
            ->where('uid', $uid)
            ->delete();
    }

    /**
     * 根据tag_ids删除
     *
     * @param array $tagIds
     * @return bool
     */
    public function deleteByTagIds($tagIds)
    {
        return self::whereIn('tag_id', $tagIds)
            ->delete();
    }
}