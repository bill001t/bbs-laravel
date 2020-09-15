<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeRelations;
use DB;

class PwLikeRelationsDao extends PwLikeRelations
{
    public function getInfo($tagid)
    {
        return self::where('tagid', $tagid)
            ->get();
    }

    public function getInfoList($tagid, $offset, $limit)
    {
        return self::where('tagid', $tagid)
            ->paginate($limit);
    }

    public function addInfo($data)
    {
        return self::create($data);
    }

    public function deleteInfo($logid, $tagid)
    {
        return self::where('logid', $logid)
            ->where('tagid', $tagid)
            ->delete();
    }

    public function deleteInfos($tagid)
    {
        return self::where('tagid', $tagid)
            ->delete();
    }

    public function deleteInfosBylogid($logid)
    {
        return self::where('logid', $logid)
            ->delete();
    }
}

?>