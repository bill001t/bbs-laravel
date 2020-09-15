<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttentionRecommendFriends;
use DB;

class PwAttentionRecommendFriendsDao extends PwAttentionRecommendFriends
{
    public function get($uid, $limit, $offset)
    {
        return self::where('uid', $uid)
            ->orderby('cnt', 'desc')
            ->paginate($limit);
    }

    public function getSameUser($uid, $recommendUid)
    {
        return self::where('uid', $uid)
            ->where('recommend_uid', $recommendUid)
            ->first();
    }

    public function getRecommend($uid)
    {
        return DB::select("SELECT a.uid,b.touid as recommend_uid,count(*) as cnt,b.uid AS same_uids FROM `pw_attention` a left join `pw_attention` b ON a.touid = b.uid  where a.uid = ? GROUP BY recommend_uid", [$uid]);
        /* $sql = $this->_bindSql("SELECT a.uid,b.touid as recommend_uid,count(*) as cnt,b.uid AS same_uids FROM `pw_attention` a left join `pw_attention` b ON a.touid = b.uid  where a.uid = 82 GROUP BY recommend_uid", $this->getTable(), $this->sqlLimit($limit, $offset));
         $result = $this->getConnection()->createStatement($sql);
         return $result->queryAll(array($uid));*/
    }

    public function batchReplace($data)
    {
        $fields = array();
        foreach ($data as $_item) {
            $_temp = array();
            $_temp['uid'] = $_item['uid'];
            $_temp['recommend_uid'] = $_item['recommend_uid'];
            $_temp['recommend_username'] = $_item['recommend_username'];
            $_temp['cnt'] = $_item['cnt'];
            $_temp['recommend_user'] = $_item['recommend_user'];

            if (!$_temp) return false;

            return self::firstOrCreate($_temp);
        }
        if (!$fields) return false;
    }

    public function replace($fields)
    {
        return self::firstOrCreate($fields);
    }

    public function _delete($uid)
    {
        return self::destroy($uid);
    }

    public function deleteByRecommend($uid, $recommendUid)
    {
        return self::where('uid', $uid)
            ->where('recommend_uid', $recommendUid)
            ->delete();
    }
}