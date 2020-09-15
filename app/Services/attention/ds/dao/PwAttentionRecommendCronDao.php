<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttentionRecommendCron;
use DB;

class PwAttentionRecommendCronDao extends PwAttentionRecommendCron
{
    public function get($uid)
    {
        return self::find($uid);
    }

    public function getAll()
    {
        return self::all();
    }

    public function replace($fields)
    {
        return self::firstOrCreate($fields);
    }

    public function _delete($uid)
    {
        return self::destroy($uid);
    }

    public function deleteByCreatedTime($created_time)
    {
        return self::where('created_time', '<', $created_time)
            ->delete();
    }

    public function _update($uid, $fields)
    {
        return self::where('uid', $uid)
            ->update($fields);
    }
}