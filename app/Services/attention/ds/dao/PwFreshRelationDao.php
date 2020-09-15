<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwFreshRelation;
use DB;

class PwFreshRelationDao extends PwFreshRelation
{
    protected $_attentionTable = 'attention';

    public function get($uid, $limit, $offset)
    {
        return self::where('uid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function fetchAttentionFreshByUid($uid, $uids, $limit, $offset)
    {
        return self::where('uid', $uid)
            ->whereIn('created_userid', $uids)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function count($uid)
    {
        return self::where('uid', $uid)
            ->count();
    }

    public function addRelation($fields)
    {
        return self::create($fields);
    }

    public function addRelationByAttention($fields)
    {
        return DB::insert('INSERT INTO ' . $this->table . ' (uid, fresh_id, type, created_userid, created_time) SELECT uid,?,?,?,? FROM ' . $this->_attentionTable . ' WHERE touid=? ORDER BY created_time DESC LIMIT 1000', [$fields['fresh_id'], $fields['type'], $fields['created_userid'], $fields['created_time'], $fields['uid']]);
    }

    public function batchAdd($fields)
    {
        $array = array();
        foreach ($fields as $key => $value) {
            $array[] = array($value['uid'], $value['fresh_id'], $value['type'], $value['created_userid'], $value['created_time']);
        }

        return self::create($array);
    }

    public function batchDelete($ids)
    {
        return self::destroy($ids);
    }

    public function deleteByUidAndCreatedUid($uid, $fromuid)
    {
        return self::where('uid', $uid)
            ->where('created_userid', $fromuid)
            ->delete();
    }

    public function deleteOver($uid, $limit)
    {
        return self::where('uid', $uid)
            ->orderby('created_time', 'asc')
            ->limit($limit)
            ->delete();
    }
}