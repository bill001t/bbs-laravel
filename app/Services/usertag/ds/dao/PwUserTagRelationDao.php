<?php

namespace App\Services\usertag\ds\dao;

use App\Core\Hook\SimpleHook;
use App\Services\usertag\ds\relation\PwUserTagRelation;

class PwUserTagRelationDao extends PwUserTagRelation
{
    /**
     * 根据用户ID获得用户的标签关系
     *
     * @param int $uid
     * @param int $tag_id
     * @return array
     */
    public function getRelationByUidAndTagid($uid, $tag_id)
    {
        return self::where('tag_id', $tag_id)
            ->where('uid', $uid)
            ->first();
    }

    /**
     * 根据用户ID获得用户的标签关系
     *
     * @param int $uid
     * @return array
     */
    public function getRelationByUid($uid)
    {
        return self::where('uid', $uid)
            ->get();
    }

    /**
     * 根据用户ID统计该用户已经拥有的标签数组
     *
     * @param int $uid
     * @return array
     */
    public function countByUid($uid)
    {
        return self::where('uid', $uid)
            ->count();
    }

    /**
     * 根据标签ID获得该标签的相关用户记录数量
     *
     * @param int $tag_id
     * @return array
     */
    public function countRelationByTagid($tag_id)
    {
        return self::where('tag_id', $tag_id)
            ->count();
    }

    /**
     * 根据标签ID获得该标签的相关用户记录
     *
     * @param int $tag_id
     * @return array
     */
    public function getRelationByTagid($tag_id, $limit, $start)
    {
        return self::where('tag_id', $tag_id)
            ->paginate($limit);
    }

    /**
     * 删除关系
     *
     * @param int $uid
     * @param int $tag_id
     * @return boolean
     */
    public function deleteRelation($uid, $tag_id)
    {
        $result = self::where('tag_id', $tag_id)
            ->where('uid', $uid)
            ->delete();
        if ($result) {
            SimpleHook::getInstance('PwUserTagRelationDao_deleteRelation')->runDo($tag_id, array(), array('used_count' => -1));
        }
        return $result;
    }

    /**
     * 批量删除关系
     *
     * @param int $uid
     * @param array $tag_ids
     * @return boolean
     */
    public function batchDeleteRelation($uid, $tag_ids)
    {
        return self::whereIn('tag_id', $tag_ids)
            ->where('uid', $uid)
            ->delete();
    }

    /**
     * 根据标签ID列表批量删除标签关系
     *
     * @param array $tag_ids
     */
    public function batchDeleteRelationByTagids($tag_ids)
    {
        return self::whereIn('tag_id', $tag_ids)
            ->delete();
    }

    /**
     * 根据用户ID删除该用户的关系
     *
     * @param int $uid
     * @return boolean
     */
    public function deleteRelationByUid($uid)
    {
        return self::where('uid', $uid)
            ->delete();
    }

    /**
     * 根据用户ID列表批量删除数据
     *
     * @param int $uid
     * @return boolean
     */
    public function batchDeleteRelationByUids($uids)
    {
        return self::whereIn('uid', $uids)
            ->delete();
    }

    /**
     * 添加关系
     *
     * @param int $uid
     * @param int $tag_id
     * @param int $created_time
     * @return boolean
     */
    public function addRelation($uid, $tag_id, $created_time)
    {
        return self::firstOrCreate(['uid' => $uid, 'tag_id' => $tag_id, 'created_time' => $created_time]);
    }
}