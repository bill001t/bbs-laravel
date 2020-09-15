<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttentionRecommendRecord;
use DB;

class PwAttentionRecommendRecordDao extends PwAttentionRecommendRecord
{
    public function getRecommendFriend($uid, $limit, $offset)
    {
        return DB::select("SELECT uid,recommend_uid,group_concat(same_uid) as same_uids,count(same_uid) as cnt FROM ' $this->tables . ' WHERE `uid` =? GROUP BY recommend_uid ORDER BY cnt DESC limit ?,?", [$uid, $offset, $limit]);
    }

    public function batchReplace($data)
    {
        foreach ($data as $_item) {
            $_temp = array();
            $_temp['uid'] = $_item['uid'];
            $_temp['recommend_uid'] = $_item['recommend_uid'];
            $_temp['same_uid'] = $_item['same_uid'];
            if (!$_temp) return false;

            self::firstOrCreate($_temp);
        }

        return true;
    }

    public function replace($fields)
    {
        return self::firstOrCreate($fields);
    }

    public function deleteRecommendFriendByUid($uid)
    {
        return self::destroy($uid);
    }

    public function deleteByUidAndSameUid($uid, $same)
    {
        return self::where('uid', $uid)
            ->where('same_uid', $same)
            ->delete();
    }

    public function deleteRecommendFriend($uid, $recommendUid)
    {
        return self::where('uid', $uid)
            ->where('recommend_uid', $recommendUid)
            ->delete();
    }
}