<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttentionRelation;
use DB;

class PwAttentionRelationDao extends PwAttentionRelation
{
    public function getTypeByUidAndTouids($uid, $touids)
    {
        return self::where('uid', $uid)
            ->whereIn('touid', $touids)
            ->get();
    }

    public function count($uid)
    {
        return DB::select('SELECT typeid, COUNT(*) AS count FROM ' . $this->table . ' WHERE uid=? GROUP BY typeid', [$uid]);
    }

    public function getUserByType($uid, $typeid, $limit, $offset)
    {
        return self::where('uid', $uid)
            ->where('typeid', $typeid)
            ->paginate($limit);
    }

    public function batchAdd($uid, $touid, $typeids)
    {
        $data = array();
        foreach ($typeids as $key => $value) {
            $data[] = array('uid' => $uid, 'touid' => $touid, 'typeid' => intval($value));
        }

        return self::create($data);
    }

    public function addUserType($uid, $touid, $typeid)
    {
        $fields = array('uid' => $uid, 'touid' => $touid, 'typeid' => intval($typeid));

        return self::firstOrCreate($fields);
    }

    public function deleteByUidAndTouidAndType($uid, $touid, $typeid)
    {
        return self::where('uid', $uid)
            ->where('typeid', $typeid)
            ->where('touid', $touid)
            ->delete();
    }

    public function deleteByUidAndTouid($uid, $touid)
    {
        return self::where('uid', $uid)
            ->where('touid', $touid)
            ->delete();
    }

    public function deleteByType($typeid)
    {
        return self::where('typeid', $typeid)
            ->delete();
    }
}