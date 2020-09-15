<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwFresh;

class PwFreshDao extends PwFresh
{
    public function getFresh($id)
    {
        return self::find($id);
    }

    public function fetchFresh($ids)
    {
        return self::whereIn('id', $ids)
            ->get();
    }

    public function countFreshByUid($uid)
    {
        return self::where('created_userid', $uid)
            ->count();
    }

    public function getFreshByUid($uid, $limit, $offset)
    {
        return self::where('created_userid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function getFreshByType($type, $srcId)
    {
        return self::where('type', $type)
            ->whereIn('src_id', $srcId)
            ->get();
    }

    public function addFresh($fields)
    {
        return self::create($fields);
    }

    public function batchDelete($ids)
    {
        return self::destroy($ids);
    }
}